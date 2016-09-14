<?php

namespace ARCore\ChatFilter\chat;

/**
 * A word list that can check itself against input strings, including  "leet speak".
 * Constructs itself from csv file.
 * To use, construct this once to build the list, so that the actual searches are quick.
 * 'Leet speak' is the practice of substituting special characters in place of regular letters in order to get around chat filters.
 * Do not get rid of all the test code in this that lets you track construction of the lists.
 * Leave the echos, var dumps, etc. commented out.
 * Class WordList
 * @package ChatFilter\chat
 */
class WordList {
    protected $isLeet;
    public $reason;
    public $foundMatch;

    public $wordListLeet = array(); // Word list with letters replaced by leet-matching patterns.
    public $wordList = array();

    /**
     * Give it the name of a csv file of the list of words, including the path.
     * @param $filename
     * @param bool|true $lookForLeet - If false, defeat the ability to chack for leet.  (Save time and  memory)
     */
    function __construct($filename, $makeLeet = true) {
        $this->isLeet = $makeLeet;
        $this->wordList = $this->csvToArray($filename);

        if ($this->isLeet) {
            // var_dump( $this->wordList);
            $this->wordListLeet = $this->makeListLeet( $this->wordList);
        }

        // echo "<br>*************************** List upon consturction: <br>";
        // foreach( $this->wordList as $word) { echo $word." <br>";}
    }

    /**
     * Add to the word list, given a csv file.
     * @param $filename
     */
    function addToList( $filename ){
        // echo "Adding filename:".$filename."<br>";
        // echo "<br>*************** List before add: <br>";
        // foreach( $this->wordList as $word) { echo $word." <br>";}

        $wordsToAdd = $this->csvToArray( $filename );
        //var_dump($wordsToAdd);

        $this->wordList = array_merge( $this->wordList, $wordsToAdd);
        if ($this->isLeet){
            $wordsToAddLeet = $this->makeListLeet($wordsToAdd);
            $this->wordListLeet =array_merge( $this->wordListLeet, $wordsToAddLeet);
        }

        // echo "********** List after adding: <br>";
        // foreach( $this->wordList as $word) { echo $word." <br>";}
    }

    /**
     * Given a word list, replace all the characters in it with regular expressions that search for leat.
     * Do this in preparation for later leet searches.
     * This utility may be used by other objects as well.
     * @param $wordListIn
     */
    public function makeListLeet(array $wordListIn) {
        $returnWordList = array();

        // Separator Characters.
        // The matcher will permit an unlimited number of any of these characters between
        // characters of the word we are trying to match.
        // This is to get rid of words like "p_o_o_p".
        //$sepChars = '(_|\||-|\+|\(|\)|\[|\]|\{|\}|\*|\.|\,|\\|\+|\<|\>|\^|\?|\=|\:|\;|\#|\@|\%|\!|\&|\$|\"|\')*';

        // This is a really confusing one to edit, so build the expresson a  little at a time in an orderly fassion:
        // Programming fact: To match a literal backslash using PHP’s preg_match() function you must use 4 backslashes.
        // It must be done like this because every backslash in a C-like string must be escaped by a backslash.
        // That would give us a regular expression with 2 backslashes, as you might have assumed at first.
        // However, each backslash in a regular expression must be escaped by a backslash, too.
        // This is the reason that we end up with 4 backslashes.
        // This pattern is to properly escape a single quote: \\\'
        $sepChars = '(\'|\!|\@|\#|\$|\%|\^|\&|\*|\(|\)|\_|\+|\-|\=';   // Top row of a Qwerty keyboard, in order.
        $sepChars .= '|\{|\}|\||\[|\]|\\\\|\:|\"|\;|\'|\<|\>|\?|\,|\.|\/|\"';   // Right side of keyboard, working our way down
        $sepChars .= '|\~|\`|\´|\d'; // remaining two in the upper left, some special German apostrophy, and number 1. Any digit
        $sepChars .= ')*'; // Closing of regex group, and quantifier (zero to unlimited times)

        // In a preg expression, '(x|y|z)' will trigger on x, y, or z.
        // This will also match any letter doubled.
        $leet_replace['a'] = '(a+|4|@|q)';
        $leet_replace['b'] = '(b+|8|ß|Β|β)';
        $leet_replace['c'] = '(c+)';
        $leet_replace['d'] = '(d+)';
        $leet_replace['e'] = '(e+|3)';
        $leet_replace['f'] = '(f+|ph)';   // ph as phonetic replacement for f
        $leet_replace['g'] = '(g+|6|9)';
        $leet_replace['h'] = '(h+)';
        $leet_replace['i'] = '(i+|1|\!)';
        $leet_replace['j'] = '(j+)';
        $leet_replace['k'] = '(k+)';
        $leet_replace['l'] = '(l+|1)';
        $leet_replace['m'] = '(m+|nn)';
        $leet_replace['n'] = '(n+)';
        $leet_replace['o'] = '(o+|0)';
        $leet_replace['p'] = '(p+)';
        $leet_replace['q'] = '(q+)';
        $leet_replace['r'] = '(r+|®)';
        $leet_replace['s'] = '(s+|5|z|\$)';    // z a replacement for s in making plurals
        $leet_replace['t'] = '(t+|7)';
        $leet_replace['u'] = '(u+|v)';
        $leet_replace['v'] = '(v+|u)';
        $leet_replace['w'] = '(w+)';
        $leet_replace['x'] = '(x+|\&|\>\<|\)\()';
        $leet_replace['y'] = '(y+)';
        $leet_replace['z'] = '(z+|s)';

        //echo( "The leet_replace array <br>");
        //foreach ( $leet_replace as $key => $value ){
        //    echo( "$key => $value <br>");
        //}

        // Build a word list with all of the individual letters replaced with regular expressions to match leet equivalents.
        // Note that the str_replace function does NOT work right for this!  It will replace the letters it just added, recursively
        // with their equivalents!  (This took hours to find.)
        // So, use loops as explicitly replace each:
        for($wordIndex = 0;$wordIndex < count($wordListIn);$wordIndex++) {
            //$returnWordList[] = '/' . str_ireplace(array_keys($leet_replace), array_values($leet_replace), $wordListIn[$index]) . '/';
            $word = $wordListIn[$wordIndex];
            $wordReplacer = '';
            // Loop through the word in question one character at a time.
            for ($letterIndex=0; $letterIndex < strlen($word); $letterIndex++){
                $char = substr( $word, $letterIndex, 1);
                if ( array_key_exists( $char, $leet_replace)){
                    $charReplacer = $leet_replace[$char];
                    $wordReplacer .= $charReplacer.$sepChars;
                } else {
                    $wordReplacer .= $char.$sepChars;
                }
            }
            $returnWordList[] = '/'.$wordReplacer.'/';
            //echo('<br>'.$wordListIn[$wordIndex].' => '.$returnWordList[$wordIndex].'<br>');
        }
        return $returnWordList;
    }

    /////////////////////////////////  CHECK IF IN LIST ///////////////////////////////////////////////////////
    /**
     * See if the $inString contains leet version of any of the words on the object's list (which are bad words.)
     * Will set this->rejectReason to a meaningful reason if it is.  Also sets $this->isProfane
     * @param $inString text to be tested.
     * @return bool true if the $inString contains a word on the list.
     */
    function checkLeet($inString)
    {
        if (!$this->isLeet) return false;
        $matches = array();
        try {
            for ($index = 0; $index < count($this->wordListLeet); $index++) {
                if (preg_match($this->wordListLeet[$index], $inString, $matches)) {
                    $this->reason = "Found:" . $this->wordList[$index] . " Match: " . $matches[0];
                    $this->foundMatch = true;
                    return true;
                }
            }
        } catch (\Exception $e){
          return false;
        }
        $this->reason = "";
        return false;
    }

    /**
     * Check to see if the input string contains one of the words.  Plain old match, not looking for leet.
     *
     * @param $inString
     */
    function checkPlain($inString) {
        for($index = 0;$index < count($this->wordList);$index++) {
            // echo($index."   ".$this->wordList[ $index] ." <br>");
            $found = strstr($inString, $this->wordList[$index]);
            if($found != "") {
                $this->reason = "Found word: $found";
                $this->foundMatch = true;
                return true;
            }
        }
        $this->reason = "";
        return false;
    }

    ///////////////////////////////////   REPLACE FROM LIST ///////////////////////////////////////////////////
    /**
     * Given an input strring, if any words (or phrases) on the wordList appear in the input string,
     * replace them with a placeholder character: ‡
     * This can be used for detecting harmless words.
     * It will also check for the word plus and 's' on the end, because that is also almost certainly harmless.
     * It only considers them harmless with a space on the left, and a space, period, question mark, or
     * exclaimation point on the right.
     * The placeholder prevents the filter from falsely catching this: "po happy op"
     * Because of the place holder, this becomes: "po ‡ op" or after spaces removed, po‡op.  Not caught.
     * Without the placeholder it would become "poop', which would be a false positive.
     * Yes, that means people could use this character to trick us as a separator.  So keep it quiet!
     * @param $inString
     * @param $replacementString
     *
     * @return mixed|string
     */
    public function replaceFromList( $inString ){
        $outString = ' '.$inString.' ';  // add space on both sides so we only get complete words
        $NSwapped = 0;
        for( $index = 0; $index < count($this->wordList); $index++){
            $count = 0;
            // Form regex expression for space - word - space or separator
            $matchPattern = '/ '.$this->wordList[$index].'[ .?!]/';
            $outString = preg_replace($matchPattern, " ‡ ", $outString, -1, $count);
            // Form regex expression for space - word - letter 's' - space or separator
            $matchPattern = '/ '.$this->wordList[$index].'s[ .?!]/';
            // echo( $matchPattern."<br>");
            $outString = preg_replace($matchPattern, " ‡ ", $outString, -1, $count);
            $NSwapped += $count;
        }
        // Now for efficiency we do not need more than one placeholder in a row.
        // This will replace repeated separators with a single one.
        $outString = preg_replace('/( ‡(\s*‡\s*)+)/',' ‡ ', $outString);

        $outString = trim($outString);

        //echo "Number of swaps: ".$NSwapped." in ".$outString."<br>";
        return $outString;
    }

    // This is a test function to display the contents of the input list against the leet replacement.
    // Useful for debugging, as well as making tables for analysis of how people are getting around
    // the filter.
    function dump() {
        // var_dump($this->wordList);
        // var_dump($this->wordListLeet);

        echo("<br>Word Entry => Leet Replacer Pattern <br>");
        for($index = 0;$index < count($this->wordList);$index++) {
            // Uses non thread-safe echo, but this function only called for testing.
            echo("<br>".$index." ".$this->wordList[$index] . " => <br>" . $this->wordListLeet[$index] . "<br>");
        }
    }

    //////////////////////////////////////////  CSV TO ARRAY ///////////////////////////////////////////////////////
    /**
     * Given a csv file, return an array of strings.
     * Anything after a semicolon is a comment.
     * Multiple entries may be made on a line, separated by comments.
     * If a single entry has spaces between words they are preserved.
     *
     * @param $filename
     *
     * @return array
     */
    public function csvToArray($filename) {
        // var_dump($filename);
        $outputArray = array();

        // If no file exists best to return empty array, rather than crash.
        if(!file_exists($filename)) {
            return $outputArray;
        }

        $rows = file($filename);
        foreach($rows as $row) {
            if($row[0] != ';') {              // First column = ';' means a comment.
                $row = strtok($row, ';');   // Get rid of comments
                $row = trim($row);
                $rowArray = explode(',', $row);
                $rowArray = array_filter($rowArray);   // Removes empty elements, which would cause crash later.
                $outputArray = array_merge($outputArray, $rowArray);
            }
        }
        // Now trim off any white space picked up in this process
        $outputArray = array_map('trim', $outputArray);
        // Get rid of any empty elements:
        $outputArray = array_filter($outputArray);
        // echo ("CSV Conversion:<br>");
        // var_dump($outputArray);
        return $outputArray;

    }
}
?>
