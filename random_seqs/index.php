<?php

// author    Joseba Bikandi
// license   GNU GPL v2
// source code available at  biophp.org


// the code in the top will manipulated the input sequence
// in the middle of the file is located the form
// in the botton are located the functions used in this script


//############################################################################
//#################      lets manipulated the sequence       #################
//############################################################################

if($_POST){
        // get procedure
        $procedure=$_POST["procedure"];

        // let's respond
        if ($procedure=="fromseq"){
                // get the sequence
                $seq=strtoupper($_POST["seq"]);
                // remove non coding from sequence (works for DNA and protein)
                $seq=preg_replace("/\W|[^ABCDEFGHIKLMNPQRSTVWXY]|\d/","",$seq);
                // get length of output sequence
                $length1=$_POST["length1"];

                if($length1){
                        // remove from sequence characters different to ACGT.
                        $seqACGT=preg_replace("/[^ACGT]/","",$seq);
                        // The sequence is DNA if A+C+G+T>70% (so, if $seqACGT is long enought)
                        if (strlen($seqACGT)>strlen($seq)*0.7){
                                // The sequence is DNA
                                // get the frequencies for each nucleotide
                                $a=0;$c=0;$g=0;$t=0;
                                $a=substr_count($seq,"A");
                                $c=substr_count($seq,"C");
                                $g=substr_count($seq,"G");
                                $t=substr_count($seq,"T");
                                $acgt=$a+$c+$g+$t;
                                // Get number of ocurrences per each nucleotide for a seq with length=$length1
                                $a2=round ($a*$length1/$acgt);
                                $c2=round ($c*$length1/$acgt);
                                $g2=round ($g*$length1/$acgt);
                                $t2=round ($t*$length1/$acgt);
                                // get randomized sequence
                                $result=randon_DNA($a2,$c2,$g2,$t2);
                        }else{
                                // The sequence is protein
                                // get the frequencies for each aminoacid
                                $A=0;$C=0;$D=0;$E=0;$F=0;$G=0;$H=0;$I=0;$K=0;$L=0;
                                $M=0;$N=0;$P=0;$Q=0;$R=0;$S=0;$T=0;$V=0;$W=0;$Y=0;
                                $A=substr_count($seq,"A");
                                $C=substr_count($seq,"C");
                                $D=substr_count($seq,"D");
                                $E=substr_count($seq,"E");
                                $F=substr_count($seq,"F");
                                $G=substr_count($seq,"G");
                                $H=substr_count($seq,"H");
                                $I=substr_count($seq,"I");
                                $K=substr_count($seq,"K");
                                $L=substr_count($seq,"L");
                                $M=substr_count($seq,"M");
                                $N=substr_count($seq,"N");
                                $P=substr_count($seq,"P");
                                $Q=substr_count($seq,"Q");
                                $R=substr_count($seq,"R");
                                $S=substr_count($seq,"S");
                                $T=substr_count($seq,"T");
                                $V=substr_count($seq,"V");
                                $W=substr_count($seq,"W");
                                $Y=substr_count($seq,"Y");
                                $ACDEFGHIKLMNPGRSTVWY=$A+$C+$D+$E+$F+$G+$H+$I+$K+$L+$M+$N+$P+$Q+$R+$S+$T+$V+$W+$Y;
                                // Get number of ocurrences per each aminoacid for a seq with length=$length1
                                $A2=round ($A*$length1/$ACDEFGHIKLMNPGRSTVWY);
                                $C2=round ($C*$length1/$ACDEFGHIKLMNPGRSTVWY);
                                $D2=round ($D*$length1/$ACDEFGHIKLMNPGRSTVWY);
                                $E2=round ($E*$length1/$ACDEFGHIKLMNPGRSTVWY);
                                $F2=round ($F*$length1/$ACDEFGHIKLMNPGRSTVWY);
                                $G2=round ($G*$length1/$ACDEFGHIKLMNPGRSTVWY);
                                $H2=round ($H*$length1/$ACDEFGHIKLMNPGRSTVWY);
                                $I2=round ($I*$length1/$ACDEFGHIKLMNPGRSTVWY);
                                $K2=round ($K*$length1/$ACDEFGHIKLMNPGRSTVWY);
                                $L2=round ($L*$length1/$ACDEFGHIKLMNPGRSTVWY);
                                $M2=round ($M*$length1/$ACDEFGHIKLMNPGRSTVWY);
                                $N2=round ($N*$length1/$ACDEFGHIKLMNPGRSTVWY);
                                $P2=round ($P*$length1/$ACDEFGHIKLMNPGRSTVWY);
                                $Q2=round ($Q*$length1/$ACDEFGHIKLMNPGRSTVWY);
                                $R2=round ($R*$length1/$ACDEFGHIKLMNPGRSTVWY);
                                $S2=round ($S*$length1/$ACDEFGHIKLMNPGRSTVWY);
                                $T2=round ($T*$length1/$ACDEFGHIKLMNPGRSTVWY);
                                $V2=round ($V*$length1/$ACDEFGHIKLMNPGRSTVWY);
                                $W2=round ($W*$length1/$ACDEFGHIKLMNPGRSTVWY);
                                $Y2=round ($Y*$length1/$ACDEFGHIKLMNPGRSTVWY);
                                // get randomized sequence
                                $result=randon_prot($A2,$C2,$D2,$E2,$F2,$G2,$H2,$I2,$K2,$L2,$M2,$N2,$P2,$Q2,$R2,$S2,$T2,$V2,$W2,$Y2);
                        }
                }else{
                        // just shuffle the sequence when length is not provided
                        $result=str_shuffle($seq);
                }
        }
        if ($procedure=="fromACGT"){
                // get the frequencies for each nucleotide
                $a=$_POST["DnaA"];
                $c=$_POST["DnaC"];
                $g=$_POST["DnaG"];
                $t=$_POST["DnaT"];
                // get length of output sequence
                $length2=$_POST["length2"];

                // Get number of ocurrences per each nucleotide
                if ($length2){
                        // in case length is specified
                        $acgt=$a+$c+$g+$t;
                        $a2=round ($a*$length2/$acgt);
                        $c2=round ($c*$length2/$acgt);
                        $g2=round ($g*$length2/$acgt);
                        $t2=round ($t*$length2/$acgt);
                }else{
                        // in case length is not specified
                        $a2=round ($a);
                        $c2=round ($c);
                        $g2=round ($g);
                        $t2=round ($t);
                }
                // get randomized sequence
                $result=randon_DNA($a2,$c2,$g2,$t2);
                $seq=$result;
        }
        if ($procedure=="fromAA"){
                // get the frequencies for each aminoacid
                $A=$_POST["A"];
                $C=$_POST["C"];
                $D=$_POST["D"];
                $E=$_POST["E"];
                $F=$_POST["F"];
                $G=$_POST["G"];
                $H=$_POST["H"];
                $I=$_POST["I"];
                $K=$_POST["K"];
                $L=$_POST["L"];
                $M=$_POST["M"];
                $N=$_POST["N"];
                $P=$_POST["P"];
                $Q=$_POST["Q"];
                $R=$_POST["R"];
                $S=$_POST["S"];
                $T=$_POST["T"];
                $V=$_POST["V"];
                $W=$_POST["W"];
                $Y=$_POST["Y"];
                // get length of output sequence
                $length3=$_POST["length3"];

                // Get number of ocurrences per each aminoacid
                if ($length3){
                        // in case length is specified
                        $ACDEFGHIKLMNPGRSTVWY=$A+$C+$D+$E+$F+$G+$H+$I+$K+$L+$M+$N+$P+$Q+$R+$S+$T+$V+$W+$Y;
                        $A2=round ($A*$length3/$ACDEFGHIKLMNPGRSTVWY);
                        $C2=round ($C*$length3/$ACDEFGHIKLMNPGRSTVWY);
                        $D2=round ($D*$length3/$ACDEFGHIKLMNPGRSTVWY);
                        $E2=round ($E*$length3/$ACDEFGHIKLMNPGRSTVWY);
                        $F2=round ($F*$length3/$ACDEFGHIKLMNPGRSTVWY);
                        $G2=round ($G*$length3/$ACDEFGHIKLMNPGRSTVWY);
                        $H2=round ($H*$length3/$ACDEFGHIKLMNPGRSTVWY);
                        $I2=round ($I*$length3/$ACDEFGHIKLMNPGRSTVWY);
                        $K2=round ($K*$length3/$ACDEFGHIKLMNPGRSTVWY);
                        $L2=round ($L*$length3/$ACDEFGHIKLMNPGRSTVWY);
                        $M2=round ($M*$length3/$ACDEFGHIKLMNPGRSTVWY);
                        $N2=round ($N*$length3/$ACDEFGHIKLMNPGRSTVWY);
                        $P2=round ($P*$length3/$ACDEFGHIKLMNPGRSTVWY);
                        $Q2=round ($Q*$length3/$ACDEFGHIKLMNPGRSTVWY);
                        $R2=round ($R*$length3/$ACDEFGHIKLMNPGRSTVWY);
                        $S2=round ($S*$length3/$ACDEFGHIKLMNPGRSTVWY);
                        $T2=round ($T*$length3/$ACDEFGHIKLMNPGRSTVWY);
                        $V2=round ($V*$length3/$ACDEFGHIKLMNPGRSTVWY);
                        $W2=round ($W*$length3/$ACDEFGHIKLMNPGRSTVWY);
                        $Y2=round ($Y*$length3/$ACDEFGHIKLMNPGRSTVWY);
                }else{
                        // in case length is not specified
                        $A2=round ($A);
                        $C2=round ($C);
                        $D2=round ($D);
                        $E2=round ($E);
                        $F2=round ($F);
                        $G2=round ($G);
                        $H2=round ($H);
                        $I2=round ($I);
                        $K2=round ($K);
                        $L2=round ($L);
                        $M2=round ($M);
                        $N2=round ($N);
                        $P2=round ($P);
                        $Q2=round ($Q);
                        $R2=round ($R);
                        $S2=round ($S);
                        $T2=round ($T);
                        $V2=round ($V);
                        $W2=round ($W);
                        $Y2=round ($Y);
                }
                // get randomized sequence
                $result=randon_prot($A2,$C2,$D2,$E2,$F2,$G2,$H2,$I2,$K2,$L2,$M2,$N2,$P2,$Q2,$R2,$S2,$T2,$V2,$W2,$Y2);
                $seq=$result;
        }

        // 70 characters per line before output
        $seq = chunk_split($seq, 70);
        $result = chunk_split($result, 70);
}else{
        $seq="";
        $result="";
        $procedure="fromseq";
}

//############################################################################
//################# we have already manipulated the sequence #################
//############################# bellow is the form ###########################
//############################################################################
?>


<html>
  <head>
    <title>Random sequences</title>
  </head>
  <body bgcolor=FFFFFF>
    <center>
    <form method='post' action="<? print $_SERVER["PHP_SELF"]; ?>">
    <H2>Random sequences</H2>
         <table cellpadding=5 cellspacing=0 width=650 border=0>
           <tr><td align=center bgcolor=FFDDDD>Select<br>method
               </td><td align=center bgcolor=FFDDDD>
                Parameters
           </td></tr>
           <tr><td valign=top bgcolor=DDFFFF>
                <input type=radio name=procedure value=fromseq<?php if ($procedure=="fromseq"){print " CHECKED";} ?>>
               </td><td bgcolor=DDFFFF>
                <B>Row sequence to be randomized <?php if($seq){print "($seqlen bp)";} ?>:</B>
                <br><font size=-2>DNA or protein nature of the sequence will be automatically detected. Non coding characters are removed by default.</font>
                <br><textarea name='seq' rows='4' cols='80'><?php print $seq; ?></textarea>
                <BR>Generate a random sequence of length <input type=text name=length1 size=5<?php if ($length1){print " value=$length1";} ?>> with composition above
                <br><font size=-2>If length is blank, the characters above will be shuffled.</font>

           </td></tr>
           <tr><td valign=top bgcolor=66FFFF>
                <input type=radio name=procedure value=fromACGT<?php if ($procedure=="fromACGT"){print " CHECKED";} ?>>
               </td><td bgcolor=66FFFF>
                Generate random DNA sequence of length <input type=text name=length2 size=5<?php if ($length2){print " value=$length2";} ?>> and composition bellow:
                <br>A: <input type=text name=DnaA size=5 value=<?php if ($a){print $a;}else{print "29.5";} ?>> &nbsp;
                    C: <input type=text name=DnaC size=5 value=<?php if ($c){print $c;}else{print "20.5";} ?>> &nbsp;&nbsp;
                    G: <input type=text name=DnaG size=5 value=<?php if ($g){print $g;}else{print "20.5";} ?>> &nbsp;&nbsp;
                    T: <input type=text name=DnaT size=5 value=<?php if ($t){print $t;}else{print "29.5";} ?>>
           </td></tr>
           <tr><td valign=top bgcolor=AAFFFF>
                <input type=radio name=procedure value=fromAA<?php if ($procedure=="fromAA"){print " CHECKED";} ?>>
               </td><td bgcolor=AAFFFF>
                Generate random protein sequence of length <input type=text name=length3 size=5<?php if ($length3){print " value=$length3";} ?>> and composition bellow:
                <br>A: <input type=text name=A size=5 value=<?php if ($A){print $A.">";}else{print "7.174> &#8240";} ?> &nbsp;&nbsp;
                    C: <input type=text name=C size=5 value=<?php if ($C){print $C.">";}else{print "2.395> &#8240";} ?> &nbsp;&nbsp;
                    D: <input type=text name=D size=5 value=<?php if ($D){print $D.">";}else{print "4.872> &#8240";} ?> &nbsp;&nbsp;
                    E: <input type=text name=E size=5 value=<?php if ($E){print $E.">";}else{print "6.662> &#8240";} ?> &nbsp;&nbsp;
                    F: <input type=text name=F size=5 value=<?php if ($F){print $F.">";}else{print "3.624> &#8240";} ?>
                <br>G: <input type=text name=G size=5 value=<?php if ($G){print $G.">";}else{print "7.532> &#8240";} ?> &nbsp;&nbsp;
                    H: <input type=text name=H size=5 value=<?php if ($H){print $H.">";}else{print "2.366> &#8240";} ?> &nbsp;&nbsp;
                    I: <input type=text name=I size=5 value=<?php if ($I){print $I.">";}else{print "4.374> &#8240";} ?> &nbsp;&nbsp;
                    K: <input type=text name=K size=5 value=<?php if ($K){print $K.">";}else{print "5.635> &#8240";} ?> &nbsp;&nbsp;
                    L: <input type=text name=L size=5 value=<?php if ($L){print $L.">";}else{print "9.412> &#8240";} ?>
                <br>M: <input type=text name=M size=5 value=<?php if ($M){print $M.">";}else{print "2.196> &#8240";} ?> &nbsp;&nbsp;
                    N: <input type=text name=N size=5 value=<?php if ($N){print $N.">";}else{print "3.789> &#8240";} ?> &nbsp;&nbsp;
                    P: <input type=text name=P size=5 value=<?php if ($P){print $P.">";}else{print "6.294> &#8240";} ?> &nbsp;&nbsp;
                    Q: <input type=text name=Q size=5 value=<?php if ($Q){print $Q.">";}else{print "4.509> &#8240";} ?> &nbsp;&nbsp;
                    R: <input type=text name=R size=5 value=<?php if ($R){print $R.">";}else{print "5.607> &#8240";} ?>
                <br>S: <input type=text name=S size=5 value=<?php if ($S){print $S.">";}else{print "7.527> &#8240";} ?> &nbsp;&nbsp;
                    T: <input type=text name=T size=5 value=<?php if ($T){print $T.">";}else{print "5.685> &#8240";} ?> &nbsp;&nbsp;
                    V: <input type=text name=V size=5 value=<?php if ($V){print $V.">";}else{print "6.026> &#8240";} ?> &nbsp;&nbsp;
                    W: <input type=text name=W size=5 value=<?php if ($W){print $W.">";}else{print "1.480> &#8240";} ?> &nbsp;&nbsp;
                    Y: <input type=text name=Y size=5 value=<?php if ($Y){print $Y.">";}else{print "2.840> &#8240";} ?>
           </td></tr>
           <tr><td>

           </td><td>
                <input type='submit' value='Sutmit'> <a href="<? print $_SERVER["PHP_SELF"]; ?>">Start</a>
           </td></tr>
         </table>
    </form>
        <table cellpadding=5 width=650 border=0>
           <tr><td align=center>
           <pre>
           <?php
           if($result!=""){
                print "<textarea rows=10 cols=80>$result</textarea>";
           }
           ?></pre>
           </td></tr>
           <tr><td>
           <b>NOTES</b>:
           <br><a href=http://www.ncbi.nlm.nih.gov/entrez/query.fcgi?cmd=retrieve&db=pubmed&list_uids=7957164&dopt=abstract>NC-UIBMB</a>
           codes are used as a reference.
           <br>Default values are based in human genome.

           <p>Source code is available at
           <a href=http://www.biophp.org/minitools/random_seqs>BioPHP.org</a>

           </td></tr>
        </table>

    </center>
  </body>
</html>

<?php
//############################################################################
//################# Functions used in this script ############################
//############################################################################


// Generate a random DNA sequence
//    $a, $c, $g and $t are the number of nucleotides A, C, G or T
// Usage example:
//     $seq = randon_DNA(200,200,200,200);
function randon_DNA($a,$c,$g,$t){
        return str_shuffle(str_repeat("A",$a).str_repeat("C",$c).str_repeat("G",$g).str_repeat("T",$t));
}


// Generate a random protein sequence
//    $a, $c, $g and $t are the number of nucleotides A, C, G or T
// Usage example:
//     $seq = randon_prot(100,100,100,100,100,100,100,100,100,100,100,100,100,100,100,100,100,100,100,100)
function randon_prot($A,$C,$D,$E,$F,$G,$H,$I,$K,$L,$M,$N,$P,$Q,$R,$S,$T,$V,$W,$Y){
        return str_shuffle(str_repeat("A",$A).str_repeat("C",$C).str_repeat("D",$D).str_repeat("E",$E).
                           str_repeat("F",$F).str_repeat("G",$G).str_repeat("H",$H).str_repeat("I",$I).
                           str_repeat("K",$K).str_repeat("L",$L).str_repeat("M",$M).str_repeat("N",$N).
                           str_repeat("P",$P).str_repeat("Q",$Q).str_repeat("R",$R).str_repeat("S",$S).
                           str_repeat("T",$T).str_repeat("V",$V).str_repeat("W",$W).str_repeat("Y",$Y));
}

//############################################################################
//############################### End of fuctions ############################
//############################################################################

?>