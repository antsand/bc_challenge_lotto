<?php

class Lotto extends Phalcon\Mvc\Model
{
	const LOTTO_PATH = __DIR__ . '/../../public/data/649.csv';
	/*
	    [0] => PRODUCT
	    [1] => DRAW NUMBER
	    [2] => SEQUENCE NUMBER
	    [3] => DRAW DATE
	    [4] => NUMBER DRAWN 1
	    [5] => NUMBER DRAWN 2
	    [6] => NUMBER DRAWN 3
	    [7] => NUMBER DRAWN 4
	    [8] => NUMBER DRAWN 5
	    [9] => NUMBER DRAWN 6
	    [10] => BONUS NUMBER
	 */
	 const DRAW_NUMBER  = 1;	
	 const SEQUENCE_NUMBER = 2;	
	 const DRAW_DATE = 3;
         const NUMBER_DRAWN_START = 4;	 
         const NUMBER_DRAWN_END = 9;	 
	 const BONUS_NUMBER = 10;

	 const LAST_LOTTO = 3620;		 

	 /*
	  *    [INPUT] -> An array of 6 numbers
	  *    [OUTPUT] -> An object of results
	  *    			overall:
	  *    			   { 
	  *    				total_earnings: number,
	  *    				total_lotto:number,
	  *    				total_spent: number
	  *    			   }	
	  *    			lotto_wins  (Note: array of object of wins more than $85 dollars)
	  *    				[      
	  *					{
	  *						id: number,  (lotto win number)
	  *						year: date, 
	  *						match: number, ( how many numbers matched)
	  *						bonus: boolean,
	  *						won: number ( the amount won)	
	  *
	  *					}
	  *    				]
	  *    This function does a couple of things:
	  *  - It gets the 649.csv data
	  *  - It formats the data by removing the top header line
	  *  - It initializes all the variables toi start the counting
	  *    process.
	  *  - It then starts to loop over all the lotto wins
	  *  - Once it loops over it starts to check how many
	  *    numbers matched, what is the total amount spent
	  *    and the win/loss ratio
	  *  - The results are then returned as an object  
	  *  
	  *
	  */
	 public function getdata($lotto) {
		 /* Let us get the 649 data and store it in a variable */ 
		$csv = array_map('str_getcsv', file($this::LOTTO_PATH));
		$header = $csv[0];	
		$total_earnings = 0;	
		array_shift($csv); # remove column header
		
		/* Test data
		 * $lotto = [9,21,25,26,31,41];
		 */
		
		$lotto_win = [];
		$total_lotto = 0;
		$total_spent =0;
		/* Loop through all the lotto wins */
		foreach($csv as $lotto_wins) {
			/* count the number of lotto wins interated*/  		
			$total_lotto++; 

			/* check if the sequence number is 0.
			 * If not zero, skip this lotto.
			 * as per the rules of the competition */	
			if ($lotto_wins[$this::SEQUENCE_NUMBER] !=0) {
				print_r($lotto_wins[1] . '\n');
				continue;
			}

			/* If this is the last lotto, break the loop */
			if($total_lotto > $this::LAST_LOTTO) {
				break;
			}

			/* check lotto wins */
			$match_count = 0;    
			$bonus = 0; 
			$each_earnings = 0;

			/* Calculate the amount spent. Depending on the
			 * lotto index the cost varies */
			if ($total_lotto >=2990) {
				$total_spent += 3;
			}
			elseif ($total_lotto >=2125 && $total_lotto <= 2989) {
				$total_spent += 2;
			}	
			elseif ($total_lotto <= 2124) {
				$total_spent += 1;
			} 

			/* Loop through the 6 numbers and see how many have matched
		     * include bonus number too. 
			 */ 	
			foreach($lotto as $lotto_num) {
				for ($i = $this::NUMBER_DRAWN_START; $i<= $this::NUMBER_DRAWN_END; $i++) {
					if ($lotto_num == $lotto_wins[$i]) {
						$match_count +=1;
					}
				}	
				/* check bonus */   
				if ($lotto_num == $lotto_wins[$this::BONUS_NUMBER]) {
					$bonus = 1;
				}	
			}

			/* Depending on how many lottos matched,
			 * count both oveall earnings as well as
			 * the amount earned in each lotto draw 
			 * */
			if ($match_count == 6) {
				$total_earnings += 5000000;
				$each_earnings =5000000;
			} elseif (($match_count ==5) && $bonus) {
				$total_earnings +=250000;
				$each_earnings =250000;
			}  elseif ($match_count == 5) {
				$total_earnings +=3000;
				$each_earnings =3000;
			}
			elseif($match_count == 4) {
				$total_earnings += 85; 
				$each_earnings = 85;
			} elseif ($match_count == 3) {
				$total_earnings +=10;
				$each_earnings = 10;
			}
			elseif (($match_count == 2) && $bonus) {
				$total_earnings +=  5;
				$each_earnings = 5;
			}
			elseif ($match_count ==2) {
				$total_earnings +=  3;
				$each_earnings = 3;
			}

			/* Formatting the return object to include
			 * an array of results that earned equal to
			 * or more than $85
			 */
			if ($match_count >= 4) {
				$win = new StdClass();
				$win->id = $lotto_wins[1];
				$win->year = $lotto_wins[3];
				$win->match = $match_count;
				$win->bonus = $bonus; 
				$win->won = $each_earnings; 
				array_push($lotto_win, $win);
			}	
	    }

		/*
		 * Create an overall object that returns
		 * the total amount spent, earned  
		 */	
		$overall = new StdClass();
		$overall->total_earnings = $total_earnings;
		$overall->total_lotto = $total_lotto; 
		$overall->total_spent = $total_spent;

		/* Combine the return objects $win object and
		 * $overall object into one $return object
		 */  
		$return_obj = new StdClass();
		$return_obj->overall = $overall;
		$return_obj->lotto_win = $lotto_win;
		
		return $return_obj;  			    
	}

	public function putdata($data, $json_true = FALSE) {
		if (!$json_true) {
			$data = json_encode($data);
		}
		return file_put_contents($this::LOTTO_PATH, $data);
	}
}

?>
