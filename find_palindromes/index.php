<!--
Title: Find Palindrome Script
Author: Joseba Bikandi
License: GNU GLP v2
-->

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
  <title>Palindromic sequences finder</title>
</head>
<body bgcolor="#ffffff">
<h1>Palindromic sequences finder<br>
</h1>
<form method="post" action="<? print $_SERVER["PHP_SELF"]; ?>">
  <textarea name="seq" cols="75" rows="10">CCAGAGCCCACGTTTACTGCAACCCTGGGGAAATGTCACCAGAGAAATGGGGGTGGTGCCAGACAATAGA
TTGTGGGAGCTATGGTTTCCATGGTAGAGTAGAAGCATCCACCATGTGTGACATTCAGCAGATGGGGCGC
TGTGGGTGGCTTGGAGCACTCTGGTTGTAACTGAGGCAGGCACAGTGTTTAGGAAGCCTGTGCAGTAATC
CAGACTGAAGGGAGGGGAAAGCCTAGACTAAGACTATGGCTGTGGGATTGAAATAGCGTTGAAGGAGCTG
ACTTTGACTCCCGGAGATGAAGGAGAAAGAGGAAATCAGAAGGGACCAAGGATGGTGAAGTTCTTAAGAG
AAACTGAGGAGGAAGAGAGGATGATGTGGTGGGAGACGTGTAGAGAGTCCTTGTAGATCTGTCATATTGA
AGGGGACTATGGTCCCAGAGGTACAGATGTCCTAAAACAGGCTGGAAAAGGGAGTCTGGAGAGAGCTTGG
TGTTGTAATGAACCATGGGGAGCCGCCTCGTTGGCCCTGTGATTACCCAGGAACTGAATAGAGAGGGGGC
CCTGGGAGACCTCAGACACTTAGAGGATATAAGGGGGTGAAAGGGGGGACCTGGCTTTGAGTCGAAGGGA
GGAGAAGGAGATTATATAGCTGAAACGTCTAAGAGAATTTGTGATCTGAGCGTTTCTACTGGGGCAAGTG
CTTCTGAAAGGCAGAGGCGGCTGAGATCTGGAAACAGGTCTGCAAATCTGGTCACTGGTCTCATTGCAGT
AACGCTGTGCGCGGTTGAGGGAGTGTATTGGGAGAAAAACCACGCGTTGTCTGTCCCGGAAGGAACAAGC
CAGTGAGAGCCGGCCTGATGGGAGGACCGGCGAAAGGGGCTTGGTGAAGCCCGCGCTCCTTGGGGGTGGG
AATGCGGGGATGGGGTGGTCGCGATGCAGGGAGGGCGACAGGGTCCAGGTCGTGCTCATAAGTTTGGAGc</textarea>
  <br>
Minimum length of palindromic sequence:
  <select name="min">
  <option>4</option>
  <option>5</option>
  <option>6</option>
  <option>7</option>
  <option>8</option>
  <option>9</option>
  <option>10</option>
  </select>
  <br>
Maximum length of palindromic sequence:
  <select name="max">

  <option>5</option>
  <option>6</option>
  <option>7</option>
  <option>8</option>
  <option>9</option>
  <option selected="selected">10</option>
  <option>11</option>
  <option>12</option>
  <option>13</option>
  <option>14</option>
  <option>15</option>
  <option>16</option>
  <option>17</option>
  <option>18</option>
  <option>19</option>
  <option>20</option>
  </select>
  <br>
  <input value="Find Palindromic sequences" type="submit"></form>
<p>&nbsp;
</p>
<hr><font size="+1"><b><u>Definitions</u></b></font>
<br>
<b>Palindromic sequence</b>:
<br>
<!-- #BeginEditable "Definition" --> A DNA sequence whose 5'-to-3'
sequence is identical on each DNA strand. The sequence is the same when
one strand is read left to right and the other strand is read right to
left. Recognition sites of many restriction enzymes are palindromic.
<hr>
Source code (PHP) is available <a href=http://www.biophp.org/minitools/find_palindromes>here</a>
</body>
</html>



<?php
error_reporting(1);

$seq=remove_useless_from_DNA($_POST["seq"]);
if($seq==""){die("No sequence available");}

$min=$_POST["min"];
$max=$_POST["max"];

$thearray=find_palindromic_seqs ($seq,$min,$max);

// PRINT ARRAY
print "Palindromic sequences with length $min to $max within string";

print "<table border=1 cellpadding=5><tr><td bgcolor=AAAAFF>Position</td><td bgcolor=AAAAFF>Sequence</td></tr>\n";
foreach ($thearray as $key => $val){
         print "<tr><td>$key</td><td>$val</td></tr>";
        }
print "</table>\n";


//print_r($thearray);



// Description for find_palindromic_seqs
//      Searches sequence for palindromic substrings
//
// Parameters
//      $seq	is the sequence to be searched
//	$min	the minimum length of palindromic sequence to be searched
//	$max	the maximum length of palindromic sequence to be searched
//
// Return
//      An array: keys are positions in genome, and values are length of palindromic sequences
//
// Requeriments:
//      DNA_is_palindrome
function find_palindromic_seqs ($seq,$min,$max){
   $result="";
   $seq_len=strlen($seq);
   for($i=0;$i<$seq_len-$min+1;$i++){
      $j=$min;
      while($j<$max+1 and ($i+$j)<=$seq_len){
      $sub_seq=substr($seq,$i,$j);
      if (DNA_is_palindrome($sub_seq)==1){
      $results [$i]=$sub_seq;
      }
      $j++;
      }

   }
   return $results;
}


// Description for DNA_is_palindrome
//      Checks whether a DNA sequeence is palindromic. 
//	When degenerate nucleotides are included in the sequence to be searched, 
// 	sequences as "AANTT" will be considered palindromic.
//
// Parameters
//      $seq	is the sequence to be searched
//
// Return
//      True or False (1 or 0)
//
// Requeriments:
//      None
function DNA_is_palindrome($seq){
        if ($seq==RevComp_DNA2($seq)){
                return TRUE;
                }else{
                return FALSE;
                }
}

// Description for RevComp_DNA2
//      Will yield the Reverse comlement of a NA sequence. Allows degenerated nucleotides
//
// Parameters
//      $seq	is the sequence 
//
// Return
//      A sequence
//
// Requeriments:
//      None
function RevComp_DNA2($seq){
 $seq= strtoupper($seq);
 $seq=strrev($seq);
 $seq=str_replace("A", "t", $seq);
 $seq=str_replace("T", "a", $seq);
 $seq=str_replace("G", "c", $seq);
 $seq=str_replace("C", "g", $seq);
 $seq=str_replace("Y", "r", $seq);
 $seq=str_replace("R", "y", $seq);
 $seq=str_replace("W", "w", $seq);
 $seq=str_replace("S", "s", $seq);
 $seq=str_replace("K", "m", $seq);
 $seq=str_replace("M", "k", $seq);
 $seq=str_replace("D", "h", $seq);
 $seq=str_replace("V", "b", $seq);
 $seq=str_replace("H", "d", $seq);
 $seq=str_replace("B", "v", $seq);
 $seq= strtoupper ($seq);
 return $seq;
}

// Description for remove_useless_from_DNA
//      Will remove non coding characters from a DNA sequence 
//
// Parameters
//      $seq	is the sequence 
//
// Return
//      A sequence
//
// Requeriments:
//      CountCount_ACGT,Count_YRWSKMDVHB 
function remove_useless_from_DNA($seq) {

        $seq=strtoupper($seq);
        $seq=preg_replace("/\W|\d/","",$seq);
        $seq=preg_replace("/X/","N",$seq);
	$len_seq=strlen($seq);
        $number_ATGC=Count_ACGT($seq);
        $number_YRWSKMDVHB=Count_YRWSKMDVHB($seq);
        $number=$number_ATGC+$number_YRWSKMDVHB+substr_count($seq,"N");
        if ($number!=$len_seq){die ("Error:<BR>Sequence is not valid.<BR>At least one letter in the sequence is unknown (not a <a href=http://www.in-silico.com/s_restriction/Nucleotide_ambiguity_code.html>NC-UIBMB</a> valid code)");}

        return ($seq);
}



// Description for Count_ACGT
//      Will count number of A, C, G and T bases in the sequence 
//
// Parameters
//      $seq	is the sequence 
//
// Return
//      A number
//
// Requeriments:
//      None
function Count_ACGT($seq){
        $cg=substr_count($seq,"A")+substr_count($seq,"T")+substr_count($seq,"G")+substr_count($seq,"C");
        return $cg;
        }

// Description for Count_YRWSKMDVHB
//      Will count number of degenerate nucleotides (Y, R, W, S, K, MD, V, H and B) in the sequence 
//
// Parameters
//      $seq	is the sequence 
//
// Return
//      A number
//
// Requeriments:
//      None
function Count_YRWSKMDVHB($c){
        $cg=substr_count($c,"Y")+substr_count($c,"R")+substr_count($c,"W")+substr_count($c,"S")+substr_count($c,"K")+substr_count($c,"M")+substr_count($c,"D")+substr_count($c,"V")+substr_count($c,"H")+substr_count($c,"B");
        return $cg;
        }
?>