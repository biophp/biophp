<html>
  <head>
    <title>Protein sequence information</title>
  </head>
  <body bgcolor=FFFFFF>
    <center>
<?

// author    Joseba Bikandi
// license   GNU GPL v2

// Developers: consider adding new options to the form

// the code in the top will manipulated the input protein sequence
// in the middle of the file is located the form
// in the bottom are located the functions used in this script


//############################################################################
//#################      lets manipulated the sequence       #################
//############################################################################
if ($_SERVER["QUERY_STRING"]=="info"){
        print_information();die();
}elseif($_POST){
        $seq=$_POST["seq"];
        $pH=$_POST["pH"];
        $data_source=$_POST["data_source"];
        $result="";

        // remove non coding (works by default)
        $seq=remove_non_coding_prot($seq);

        // we will save the original sequence, just in case subsequence is used
        $original_seq = chunk_split($seq, 70);
        // if subsequence is requested
        if ($_POST["start"] or $_POST["end"]){
            if($_POST["start"]!=""){$start=$_POST["start"]-1;}else{$start=0;}
            if($_POST["end"]!=""){$end=$_POST["end"];}else{$end=strlen($seq);}
            $seq=substr($seq,$start,$end-$start);
            $result.="<p><b>Subsequence used for calculations:</b><br>".chunk_split($seq, 70);

        }


        // length of sequence
        $seqlen=strlen($seq);

        // compute requested parameter
        if ($_POST["composition"]==1 or $_POST["molweight"]==1 or $_POST["abscoef"]==1 or $_POST["charge"]==1 or $_POST["charge2"]==1){
                    // calculate nucleotide conposition
                    $aminoacid_content=aminoacid_content($seq);
                    // prepare nucleotide composition to be printed out
                    if ($_POST["composition"]==1){
                        $result.="<p><b>Aminoacid composition of protein:</b><br>".print_aminoacid_content($aminoacid_content);
                    }
        }

        if ($_POST["molweight"]==1 or $_POST["abscoef"]==1){
                    // calculate molecular weight of protein
                    $molweight=protein_molecular_weight($seq,$aminoacid_content);
                    if ($_POST["molweight"]==1){
                        $result.="<p><b>Molecular weight:</b><br>$molweight Daltons";
                    }
        }

        if ($_POST["abscoef"]==1){
                    $abscoef=molar_absorption_coefficient_of_prot($seq,$aminoacid_content,$molweight);
                    $result.="<p><b>Molar Absorption Coefficient at 280 nm:</b><br>".round($abscoef,2);
        }

        if ($_POST["charge"]==1){
                    // get pk values for charged aminoacids
                    $pK=pK_values ($data_source);
                    // calculate isoelectric point of protein
                    $charge=protein_isoelectric_point($pK,$aminoacid_content);
                    $result.="<p><b>Isoelectric point of sequence ($data_source):</b><br>".round($charge,2);
        }
        if ($_POST["charge2"]==1){
                    // get pk values for charged aminoacids
                    $pK=pK_values ($data_source);
                    // calculate charge of protein at requested pH
                    $charge=protein_charge($pK,$aminoacid_content,$pH);
                    $result.="<p><b>Charge of sequence at pH = $pH ($data_source):</b><br>".round($charge,2);
        }

         // colored sequence based in plar/non-plar/charged aminoacids
        if ($_POST["3letters"]==1){
                // get the colored sequence (html code)
                $three_letter_code=seq_1letter_to_3letter($seq);
                // add to result
                $result.="<p><b>Sequence as three letters aminoacid code:</b><br>".$three_letter_code;

        }
        // 50 characters per line before output
        $seq = chunk_split($seq, 70);

        // colored sequence based in polar/non-plar/charged aminoacids
        if ($_POST["type1"]==1){
                // get the colored sequence (html code)
                $colored_seq=protein_aminoacid_nature1($seq);
                // add to result
                $result.="<p><b><font color=Magenta>Polar</font>, <font color=yellow>Nonpolar</font></b> or <b><font color=red>Charged</font></b> aminoacids:<br>".$colored_seq;

        }
        // colored sequence based in polar/non-plar/charged aminoacids
        if ($_POST["type2"]==1){
                // get the colored sequence (html code)
                $colored_seq=protein_aminoacid_nature2($seq);
                // add to result
                $result.="<p><b><font color=magenta>Polar</font>, <font color=yellow>small non-polar</font>, <font color=green>hydrophobic</font>, <font color=red>negatively</font></b> or <b><font color=blue>positively</font> charged</b> aminoacids:<br>".$colored_seq;

        }
}else{
        $seq="ARNDCEQGHILKMFPSTWYVX*";
        $original_seq=$seq;
        $seqlen=strlen($seq);
        $pH=7.0;
}

//############################################################################
//################# we have already manipulated the sequence #################
//############################# bellow is the form ###########################
//############################################################################
?>

    <H2>Protein sequence information</H2>
    <form method='post' action="<? print $_SERVER["PHP_SELF"]; ?>">
         <table cellpadding=5 width=650 border=0 bgcolor=DDFFFF>
           <tr><td>
                <B>Sequence <?php if($seq){print "(length: $seqlen)";} ?>:</B>
           </td></tr>
           <tr><td>
               <textarea name='seq' rows='4' cols='80'><?php print $original_seq; ?></textarea>
           </td></tr>
           <tr><td>
                Select subsequence from position <input type=text name=start size=4 value="<? if ($_POST["start"]==1){print $_POST["start"];} ?>"> to <input type=text name=end size=4 value="<? if ($_POST["end"]==1){print $_POST["end"];} ?>"> (both included) for computation
                <hr><input type=checkbox name=composition value=1<? if ($_POST["composition"]==1){print " checked";} ?>>Aminoacid composition
                <br><input type=checkbox name=molweight value=1<? if ($_POST["molweight"]==1){print " checked";} ?>>Molecular weight
                <br><input type=checkbox name=abscoef value=1<? if ($_POST["abscoef"]==1){print " checked";} ?>>Molar absorption coefficient
                <br><input type=checkbox name=charge value=1<? if ($_POST["charge"]==1){print " checked";} ?>>Protein isoelectric point with pK values from
                        <select name=data_source>
                                <option<? if ($data_source=="EMBOSS"){print " selected";} ?>>EMBOSS
                                <option<? if ($data_source=="DTASelect"){print " selected";} ?>>DTASelect
                                <option<? if ($data_source=="Solomon"){print " selected";} ?>>Solomon
                        </select>
                <br><input type=checkbox name=charge2 value=1<? if ($_POST["charge2"]==1){print " checked";} ?>>Charge at pH = <input type=text name=pH value="<? print $pH ?>" size=4>
                <br><input type=checkbox name=3letters value=1<? if ($_POST["3letters"]==1){print " checked";} ?>>Show sequence as 3 letters aminoacid code
                <br><input type=checkbox name=type1 value=1<? if ($_POST["type1"]==1){print " checked";} ?>>Show polar, non-polar and charged nature of aminoacids
                <br><input type=checkbox name=type2 value=1<? if ($_POST["type2"]==1){print " checked";} ?>>Show polar, non-polar, Hydrofobic, and negatively or positively charged nature of aminoacids
           </td></tr>

           <tr><td align=center>
                <input type='submit' value='Sutmit'>
                <div align=right><a href=?info>Info</a></div>
           </td></tr>
           <?php if($result!=""){print "<tr><td bgcolor=FFFFFF><pre>$result</pre></td></tr>";} ?>
         </table>

    </form>


<?php
//############################################################################
//####################### The form is finished  ##############################
//############################################################################

//############################################################################
//################# Functions used in this script ############################
//############################################################################


function remove_non_coding_prot($seq) {
        // change the sequence to upper case
        $seq=strtoupper($seq);
        // remove non-coding characters([^ARNDCEQGHILKMFPSTWYVX\*])
        $seq = preg_replace ("([^ARNDCEQGHILKMFPSTWYVX\*])", "", $seq);
        return $seq;
}
function protein_isoelectric_point($pK, $aminoacid_content) {
        // At isoelectric point, charge of protein will be 0
        // To calculate pH where charge is 0 a loop is required
        // The loop will start computing charge of protein at pH=7, and if charge is not 0, new charge value will be computed
        //    by using a different pH. Procedure will be repeated until charge is 0 (at isoelectric point)
        $pH=7;          // pH value at start
        $delta=4;       // this parameter will be used to modify pH when charge!=0. The value of $delta will change during the loop
        while(1) {
                // compute charge of protein at corresponding pH (uses a function)
                $charge=protein_charge($pK,$aminoacid_content,$pH);
                // check whether $charge is 0 (consecuentely, pH will be the isoelectric point
                if (round($charge,4)==0){break;}
                // next line to check how computation is perform
                // print "<br>$charge\t$pH";
                // modify pH for next round
                if ($charge > 0) {$pH = $pH + $delta;}else{$pH = $pH - $delta;}
                // reduce value for $delta
                $delta = $delta/2;
        }
        // return pH at which charge=0 (the isoelectric point) with two decimals
        return round($pH,2);
}
function partial_charge($val1,$val2){
        // compute concentration ratio
        $cr=pow(10,$val1-$val2);
        // compute partial charge
        $pc=$cr/($cr+1);
        return $pc;
}
// computes protein charge at corresponding pH
function protein_charge($pK,$aminoacid_content,$pH){
        $charge = partial_charge($pK["N_terminus"],$pH);
        $charge+= partial_charge($pK["K"],$pH)*$aminoacid_content["K"];
        $charge+= partial_charge($pK["R"],$pH)*$aminoacid_content["R"];
        $charge+= partial_charge($pK["H"],$pH)*$aminoacid_content["H"];
        $charge-= partial_charge($pH,$pK["D"])*$aminoacid_content["D"];
        $charge-= partial_charge($pH,$pK["E"])*$aminoacid_content["E"];
        $charge-= partial_charge($pH,$pK["C"])*$aminoacid_content["C"];
        $charge-= partial_charge($pH,$pK["Y"])*$aminoacid_content["Y"];
        $charge-= partial_charge($pH,$pK["C_terminus"]);
        return $charge;
}
function pK_values ($data_source){
        // pK values for each component (aa)
        if ($data_source=="EMBOSS"){
                $pK=array(
                        "N_terminus"=>8.6,
                        "K"=>10.8,
                        "R"=>12.5,
                        "H"=>6.5,
                        "C_terminus"=>3.6,
                        "D"=>3.9,
                        "E"=>4.1,
                        "C"=>8.5,
                        "Y"=>10.1
                );
        }elseif ($data_source=="DTASelect"){
                $pK=array(
                        "N_terminus"=>8,
                        "K"=>10,
                        "R"=>12,
                        "H"=>6.5,
                        "C_terminus"=>3.1,
                        "D"=>4.4,
                        "E"=>4.4,
                        "C"=>8.5,
                        "Y"=>10
                );
        }elseif ($data_source=="Solomon"){
                $pK=array(
                        "N_terminus"=>9.6,
                        "K"=>10.5,
                        "R"=>125,
                        "H"=>6.0,
                        "C_terminus"=>2.4,
                        "D"=>3.9,
                        "E"=>4.3,
                        "C"=>8.3,
                        "Y"=>10.1
                );
        }
        return $pK;
}


function print_aminoacid_content($aminoacid_content) {
        $results="";
        foreach($aminoacid_content as $aa => $count){
                $results.="$aa\t".seq_1letter_to_3letter ($aa)."\t$count\n";
        }
        return $results;
}

function aminoacid_content($seq) {
        $array=array("A"=>0,"R"=>0,"N"=>0,"D"=>0,"C"=>0,"E"=>0,"Q"=>0,"G"=>0,"H"=>0,"I"=>0,"L"=>0,
                     "K"=>0,"M"=>0,"F"=>0,"P"=>0,"S"=>0,"T"=>0,"W"=>0,"Y"=>0,"V"=>0,"X"=>0,"*"=>0);
        for($i=0; $i<strlen($seq);$i++){
                $aa=substr($seq,$i,1);
                $array[$aa]++;
        }
        return $array;
}

function molar_absorption_coefficient_of_prot($seq,$aminoacid_content,$molweight) {
        // Prediction of the molar absorption coefficient of a protein
        // Pace et al. . Protein Sci. 1995;4:2411-23.
        $abscoef = ($aminoacid_content["A"]*5500 + $aminoacid_content["Y"]*1490 + $aminoacid_content["C"]*125)/$molweight;
        return $abscoef;
}

// molecular weight calculation
function protein_molecular_weight ($seq,$aminoacid_content){
        $molweight = $aminoacid_content["A"]*71.07;         // for Alanine
        $molweight+= $aminoacid_content["R"]*156.18;        // for Arginine
        $molweight+= $aminoacid_content["N"]*114.08;        // for Asparagine
        $molweight+= $aminoacid_content["D"]*115.08;        // for Aspartic Acid
        $molweight+= $aminoacid_content["C"]*103.10;        // for Cysteine
        $molweight+= $aminoacid_content["Q"]*128.13;        // for Glutamine
        $molweight+= $aminoacid_content["E"]*129.11;        // for Glutamic Acid
        $molweight+= $aminoacid_content["G"]*57.05;         // for Glycine
        $molweight+= $aminoacid_content["H"]*137.14;        // for Histidine
        $molweight+= $aminoacid_content["I"]*113.15;        // for Isoleucine
        $molweight+= $aminoacid_content["L"]*113.15;        // for Leucine
        $molweight+= $aminoacid_content["K"]*128.17;        // for Lysine
        $molweight+= $aminoacid_content["M"]*131.19;        // for Methionine
        $molweight+= $aminoacid_content["F"]*147.17;        // for Phenylalanine
        $molweight+= $aminoacid_content["P"]*97.11;         // for Proline
        $molweight+= $aminoacid_content["S"]*87.07;         // for Serine
        $molweight+= $aminoacid_content["T"]*101.10;        // for Threonine
        $molweight+= $aminoacid_content["W"]*186.20;        // for Tryptophan
        $molweight+= $aminoacid_content["Y"]*163.17;        // for Tyrosine
        $molweight+= $aminoacid_content["Z"]*99.13;         // for Valine
        $molweight+= 18.02;                     // water
        $molweight+= $aminoacid_content["X"]*114.822;       // for unkwon aminoacids, add avarage of all aminoacids
        return $molweight;

}
// this function has not been used in this script, but may be interesting for you
function identify_aminoacid_complete_name ($aa){
        $aa=strtoupper($aa);
        if (strlen($aa)==1){
                if ($aa=="A"){return "Alanine";}
                if ($aa=="R"){return "Arginine";}
                if ($aa=="N"){return "Asparagine";}
                if ($aa=="D"){return "Aspartic Acid";}
                if ($aa=="C"){return "Cysteine";}
                if ($aa=="E"){return "Glutamic Acid";}
                if ($aa=="Q"){return "Glutamine";}
                if ($aa=="G"){return "Glycine";}
                if ($aa=="H"){return "Histidine";}
                if ($aa=="I"){return "Isoleucine";}
                if ($aa=="L"){return "Leucine";}
                if ($aa=="K"){return "Lysine";}
                if ($aa=="M"){return "Methionine";}
                if ($aa=="F"){return "Phenylalanine";}
                if ($aa=="P"){return "Proline";}
                if ($aa=="S"){return "Serine";}
                if ($aa=="T"){return "Threonine";}
                if ($aa=="W"){return "Tryptophan";}
                if ($aa=="Y"){return "Tyrosine";}
                if ($aa=="V"){return "Valine";}
        }elseif (strlen($aa)==3){
                if ($aa=="ALA"){return "Alanine";}
                if ($aa=="ARG"){return "Arginine";}
                if ($aa=="ASN"){return "Asparagine";}
                if ($aa=="ASP"){return "Aspartic Acid";}
                if ($aa=="CYS"){return "Cysteine";}
                if ($aa=="GLU"){return "Glutamic Acid";}
                if ($aa=="GLN"){return "Glutamine";}
                if ($aa=="GLY"){return "Glycine";}
                if ($aa=="HIS"){return "Histidine";}
                if ($aa=="ILE"){return "Isoleucine";}
                if ($aa=="LEU"){return "Leucine";}
                if ($aa=="LYS"){return "Lysine";}
                if ($aa=="MET"){return "Methionine";}
                if ($aa=="PHE"){return "Phenylalanine";}
                if ($aa=="PRO"){return "Proline";}
                if ($aa=="SER"){return "Serine";}
                if ($aa=="THR"){return "Threonine";}
                if ($aa=="TRP"){return "Tryptophan";}
                if ($aa=="TYR"){return "Tyrosine";}
                if ($aa=="VAL"){return "Valine";}
        }
}
function seq_1letter_to_3letter ($seq){
        $seq = chunk_split($seq,1,'#');
        $seq = chunk_split($seq, 40);
        for($i=0; $i<strlen($seq); $i++){
                $seq = preg_replace ("(A\#)","Ala", $seq);
                $seq = preg_replace ("(R\#)","Arg", $seq);
                $seq = preg_replace ("(N\#)","Asp", $seq);
                $seq = preg_replace ("(D\#)","Asn", $seq);
                $seq = preg_replace ("(C\#)","Cys", $seq);
                $seq = preg_replace ("(E\#)","Glu", $seq);
                $seq = preg_replace ("(Q\#)","Gln", $seq);
                $seq = preg_replace ("(G\#)","Gly", $seq);
                $seq = preg_replace ("(H\#)","His", $seq);
                $seq = preg_replace ("(I\#)","Ile", $seq);
                $seq = preg_replace ("(L\#)","Leu", $seq);
                $seq = preg_replace ("(K\#)","Lys", $seq);
                $seq = preg_replace ("(M\#)","Met", $seq);
                $seq = preg_replace ("(F\#)","Phe", $seq);
                $seq = preg_replace ("(P\#)","Pro", $seq);
                $seq = preg_replace ("(S\#)","Ser", $seq);
                $seq = preg_replace ("(T\#)","Thr", $seq);
                $seq = preg_replace ("(W\#)","Trp", $seq);
                $seq = preg_replace ("(Y\#)","Tyr", $seq);
                $seq = preg_replace ("(V\#)","Val", $seq);
                $seq = preg_replace ("(X\#)","XXX", $seq);
                $seq = preg_replace ("(\*\#)","*** ", $seq);
         }
         return $seq;

}


function protein_aminoacid_nature1($seq){
        $result="";
        for($i=0; $i<strlen($seq); $i++){
                // non-polar aminoacids, magenta
                if (strpos(" GAPVILFM",substr($seq,$i,1))>0){$result.="<font color=yellow>".substr($seq,$i,1)."</font>";continue;}
                // polar aminoacids, magenta
                if (strpos(" SCTNQHYW",substr($seq,$i,1))>0){$result.="<font color=magenta>".substr($seq,$i,1)."</font>";continue;}
                // charged aminoacids, red
                if (strpos(" DEKR",substr($seq,$i,1))>0){$result.="<font color=red>".substr($seq,$i,1)."</font>";continue;}

        }
        return $result;
}

function protein_aminoacid_nature2($seq){
        $result="";
        for($i=0; $i<strlen($seq); $i++){
                // Small nonpolar (yellow)
                if (strpos(" GAST",substr($seq,$i,1))>0){$result.="<font color=yellow>".substr($seq,$i,1)."</font>";continue;}
                // Small hydrophobic (green)
                if (strpos(" CVILPFYMW",substr($seq,$i,1))>0){$result.="<font color=green>".substr($seq,$i,1)."</font>";continue;}
                // Polar
                if (strpos(" DQH",substr($seq,$i,1))>0){$result.="<font color=magenta>".substr($seq,$i,1)."</font>";continue;}
                // Negatively charged
                if (strpos(" NE",substr($seq,$i,1))>0){$result.="<font color=red>".substr($seq,$i,1)."</font>";continue;}
                // Positively charged
                if (strpos(" KR",substr($seq,$i,1))>0){$result.="<font color=red>".substr($seq,$i,1)."</font>";continue;}

        }
        return $result;
}
// Chemical group/aminoacids:
//   L/GAVLI       Amino Acids with Aliphatic R-Groups
//   H/ST          Non-Aromatic Amino Acids with Hydroxyl R-Groups
//   M/NQ          Acidic Amino Acids
//   R/FYW         Amino Acids with Aromatic Rings
//   S/CM          Amino Acids with Sulfur-Containing R-Groups
//   I/P           Imino Acids
//   A/DE          Acidic Amino Acids
//   C/KRH         Basic Amino Acids
//   */*
//   X/X
function protein_aminoacids_chemical_group($amino_seq){
        $chemgrp_seq = "";
        $ctr = 0;
        while(1)
                {
                $amino_letter = substr($amino_seq, $ctr, 1);
                if ($amino_letter == "") break;
                if (strpos(" GAVLI", $amino_letter)>0) $chemgrp_seq .= "L";
                elseif (($amino_letter == "S") or ($amino_letter == "T")) $chemgrp_seq .= "H";
                elseif (($amino_letter == "N") or ($amino_letter == "Q")) $chemgrp_seq .= "M";
                elseif (strpos(" FYW", $amino_letter)>0) $chemgrp_seq .= "R";
                elseif (($amino_letter == "C") or ($amino_letter == "M")) $chemgrp_seq .= "S";
                elseif ($amino_letter == "P") $chemgrp_seq .= "I";
                elseif (($amino_letter == "D") or ($amino_letter == "E")) $chemgrp_seq .= "A";
                elseif (($amino_letter == "K") or ($amino_letter == "R") or ($amino_letter == "H"))
                        $chemgrp_seq .= "C";
                elseif ($amino_letter == "*") $chemgrp_seq .= "*";
                elseif ($amino_letter == "X" or $amino_letter == "N") $chemgrp_seq .= "X";
                else die("Invalid amino acid symbol in input sequence.");
                $ctr++;
                }
        return $chemgrp_seq;
}

function print_information (){
?>
<table width=600><tr><td>
<b>NOTES</b>:
<br>Non-coding characters will be removed by default.
<br><a href=http://www.ncbi.nlm.nih.gov/entrez/query.fcgi?cmd=retrieve&db=pubmed&list_uids=7957164&dopt=abstract>NC-UIBMB</a> codes are used as a reference.
<p>
<b>Computation</b>:
<br>Molecular Weight:
<pre> MW =(A*71.07)+(R*156.18)+(nN*114.08)+(nD*115.08)+(nC*103.10)+
  +(nQ*128.13)+(nE*129.11)+(nG*57.05)+(nH*137.14)+(nI*113.15)+
  +(nL*113.15)+(nK*128.17)+(nM*131.19)+(nF*147.17)+(nP*97.11)+
  +(nS*87.07)+(nT*101.10)+(nW*186.20)+(nY*163.17)+(nV*99.13)+18.02</pre>
<p>Molar absorption coefficient: <a href=http://www.ncbi.nlm.nih.gov/entrez/query.fcgi?cmd=Retrieve&db=pubmed&dopt=Abstract&list_uids=8563639>Pace et al., 1995</a>
<p>Isoelectric point estimation: <a href=http://fields.scripps.edu/DTASelect/20010710-pI-Algorithm.pdf>Tabb D., 2003</a>
<p>Type of aminoacid (1):
<br>&nbsp; &nbsp; Polar: SCTNQHYW
<br>&nbsp; &nbsp; Non-Polar: GAPVILFM
<br>&nbsp; &nbsp; Charged: DEKR
<p>Type of aminoacid (2):
<br>&nbsp; &nbsp; Small non-polar: SCTNQHYW (Yellow)
<br>&nbsp; &nbsp; Hydrophobic: CVILPFYMW (Green)
<br>&nbsp; &nbsp; Polar: DQH (Magenta)
<br>&nbsp; &nbsp; Negatively charged: NE (Red)
<br>&nbsp; &nbsp; Positively charged: KR (Blue)

<hr>
<a href="<? print $_SERVER["PHP_SELF"]; ?>">Use the tool</a>
</td></tr></table>

<?
}

//############################################################################
//############################### End of fuctions ############################
//############################################################################
?>
    <br>Source code is available at <a href=http://www.biophp.org/minitools/protein_properties>BioPHP.org</a>
    </center>
  </body>
</html>