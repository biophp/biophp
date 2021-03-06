<? 
/*
In silico PCR amplification
Author: Joseba Bikandi
*/

if (!$_POST){print_form (); die();}



// GET DATA
// All non-word characters (\\W) and digits(\\d) are remove from primers and from sequence file
	// primer 1
	$primer1=strtoupper ($_POST["primer1"]);
	$primer1=preg_replace("/\\W|\\d/","",$primer1);
	// primer 2
	$primer2=strtoupper ($_POST["primer2"]);
	$primer2=preg_replace("/\\W|\\d/","",$primer2);
	// sequence
	$sequence= strtoupper ($_POST["sequence"]);
	$sequence=preg_replace("/\\W|\\d/","",$sequence);
	// maximum length of amplicons
	$maxlength=$_POST["length"];


// SET PATTERNS FROM PRIMERS
	// Change N to point in primers
	$pattern1=str_replace("N", ".", $primer1);
	$pattern2=str_replace("N", ".", $primer2);
	
	// If one missmatch is allowed, create new pattern
	// example: pattern="ACGT"; to allow one missmatch pattern=".CGT|A.GT|AC.T|ACG."
	if ($_POST["allowmissmatch"]==1){
		$pattern1=includeN($primer1);
		$pattern2=includeN($primer2);
		}

	// SET PATTERN	
	$start_pattern="$pattern1|$pattern2";
	$end_pattern=RevComp($start_pattern);
	
	

// CALL Amplify FUNCTION
	
	$results_array=Amplify($start_pattern,$end_pattern,$sequence,$maxlength);


// PRINT RESULTS
	print "<pre>Primer 1: $primer1\\n";
	print "Primer 2: $primer2\\n\\n";
	
	if (sizeof($results_array)>0){	
	print "List of amplicons: position in sequence, length and sequence\\n\\n";
		foreach($results_array as $key => $val){
			print "$key	$val	".substr($sequence,$key,$val)."\\n";
			}
	}else{
	print "No amplification\\n\\n";
	}


// ##############################################################
//                       FUNCTIONS
// ##############################################################
				
function Amplify ($start_pattern,$end_pattern,$sequence,$maxlength){

	// SPLIT SEQUENCE BASED IN $start_pattern (start positions of amplicons)

	$fragments = preg_split("/($start_pattern)/", $sequence,-1,PREG_SPLIT_DELIM_CAPTURE);

	$maxfragments=sizeof($fragments);
	$position=strlen($fragments[0]);
	$mn=0;
	for ($m=1;$m<$maxfragments; $m+=2){
	
		$subfragment_to_maximum=substr($fragments[$m+1],0,$maxlength);
		$fragments2 = preg_split("/($end_pattern)/", $subfragment_to_maximum,-1,PREG_SPLIT_DELIM_CAPTURE);

		if (sizeof($fragments2)>1){
			$lenfragment=strlen($fragments[$m].$fragments2[0].$fragments2[1]);
			$results_array[$position]=$lenfragment;		
			}
		$position+=strlen($fragments[$m])+strlen($fragments[$m+1]);
	}
	
return($results_array);
}




// ####################
	
function RevComp($p2){
$p2=strrev($p2);
$p2=str_replace("A", "t", $p2);
$p2=str_replace("T", "a", $p2);
$p2=str_replace("G", "c", $p2);
$p2=str_replace("C", "g", $p2);
$p2 = strtoupper ($p2);
return $p2;
}

// ####################
function includeN($pattern) {
	if (strlen($pattern)>2){
		$new_pattern=".".substr($pattern,1);
		$pos=1;
		while ($pos<strlen($pattern)){
			$new_pattern.="|".substr($pattern,0,$pos).".".substr($pattern,$pos+1);
			$pos++;
			}
	}
return ($new_pattern);
}

// ####################
function print_form () {
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html><head><title>In silico PCR amplification</title></head>

<body bgcolor="#ffffff">
                              

<center>

<h1 align="center"><i>In silico</i> PCR amplification</h1>

<form method="post" action="<? print $_SERVER["PHP_SELF"]; ?>"> 
 
<table align="center" border="1" cellpadding="10">
<tbody><tr>
<td bgcolor="#ccffff" valign="top">

<b>Sequence</b><br>
<textarea name="sequence" cols="75" rows="10">GGAGTGAGGG GAGCAGTTGG GAACAGATGG TCCCCGCCGA GGGACCGGTG GGCGACGGCG 60
AGCTGTGGCA GACCTGGCTT CCTAACCACG TCGTGTTCTT GCGGCTCCGG CCCCTGCGGC 120
GACGCTCAGA TCCAACCGAA GCTGAGAAAC CAGCTTCTTC GTCGTTGCCT TCGTCGCCGC 180
CGCCGCAGTT GCTGACGAGA GAGGAGTTGG TTGGCCTCGG CGGAGAGCTT TTCCTGTGGG 240
ACGGAGAAGA CAGCTCCTTC TTAGTCGTTC GCCTTCGGGG CCCCAGCGGC GGCGGCGAAG 300
 </textarea><br>
<b>Primer 1</b><sup><font size="1">1</font></sup> &nbsp; &nbsp;<small><small>5'-</small></small> <input size="30" name="primer1" value="GAGCAGTTGG" type="text"><small><small>-3'</small></small><br>
<b>Primer 2</b><sup><font size="1">1</font></sup> &nbsp; &nbsp;<small><small>5'-</small></small> <input size="30" name="primer2" value="GCCGCTGGGG" type="text"><small><small>-3'</small></small><br>

<p>

<input name="allowmissmatch" value="1" type="checkbox"> Allow one mismatch <sup><font size="1">2</font></sup>
<br><br>
<b>Maximum lenght of bands</b><br>
&nbsp;<input size="10" name="length" value="3000" type="text"> nucleotides<br>
</p><p>
<font size="2">
<sup><font size="1">1</font></sup> A,T,G,C and N are allowed; A+T+G+C must be 10 or more.
<br>
<sup><font size="1">2</font></sup> One mismatch is allowed in any position of primers. 
</font>
</p></td>

</tr>
                                                                        
                                          
</tbody></table>
                             
<div align="center"><br>
<input value="Amplify" type="submit"> <input value="Reset" type="reset"></div>
</form>
<br>

<a href="http://biophp.in-silico.com/search.php?PCR+Amplification">Source code</a>
</center>


</body></html>

<?
}
?>