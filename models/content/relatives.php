<?php 
/*
cpl: Current Parrent Level
ccl: Current Child Level
*/

function get_relation_string( $gender , $cpl = 0 , $ccl = 0 )
{
$relation = "";
	switch ($cpl)
	{
		case 0:
			switch ($ccl)
			{
				case 1:
					$relation = $gender == "male" ? "Son" : "Daughter"; 
				break;
				
				case 2:
					$relation = $gender == "male" ? "Grandson" : "Granddaughter"; 
				break;
				
				case 3:
					$relation = $gender == "male" ? "Great grandchild" : "Great-granddaughter"; 	
				break;
				
				case 4:
					$relation = $gender == "male" ? "...." : "....";
			}
		break;
		case 1:
			$relation = $gender == "male" ? "Father" : "Mother"; 
			if($ccl)
				switch ($ccl)
				{
					case 1:
						$relation = $gender == "male" ? "Brother" : "Sister"; 
					break;
					case 2:
						$relation = $gender == "male" ? "Nephew" : "Niece"; 
					break;
					case 3:				
						$relation = $gender == "male" ? "...." : "...."; 				
					break;
					
					case 4:
						$relation = $gender == "male" ? "...." : "....";
				}
		break;
		case 2:
			$relation = $gender == "male" ? "Grandfather" : "Grandmother"; 
			if($ccl)
				switch ($ccl)
				{
					case 1:
						$relation = $gender == "male" ? "Uncle" : "Aunt"; 
					break;
					case 2:
						$relation = $gender == "male" ? "Cousin" : "Cousin"; 
					break;
					case 3:				
						$relation = $gender == "male" ? "...." : "...."; 				
					break;
					
					case 4:
						$relation = $gender == "male" ? "...." : "....";
				}
		break;
	}
return $relation;
}

//TODO Όταν ο χρήστης προσθέτει μια επαφή αν βάζει γονείς, σε αυτούς που έβαλε ότι τους έχει γονείς θα πρέπει να
//ενημερώνεται το πεδίο τους number_of_childs.
function rear_flashback_relatives( $parrentid, $parrent_gender, $cpl = 0, $ccl = 0, $number_of_childs, &$stack, &$array )
{
	if( $number_of_childs && ( NULL != ( $Records = get_childs_from_parrent( $parrentid, $parrent_gender, dump( $stack, "dump", 0) ) ) ) ) {
		$ccl++;
		foreach( $Records as $person) {
			$relation = get_relation_string( $person['sex'] , $cpl , $ccl );
			echo "<p class='Debug1'>L:[".$cpl."][".$ccl."]|ID:".$person['personid']."|".$relation."|=> ".$person['name']." ".$person['surname']."</p>";

			//Εύρεση του συζύγου
			//$parrentid = ($parrent_gender == "male") ? ($person['motherid'] ? $person['motherid'] : 0) : ($person['fatherid'] ? $person['fatherid'] : 0);
			//$consort = get_parrents( $parrentid );
			//dump($parrentid);
			//dump($consort[0] , "Σύζυγος");
			
			if ( !in_array( $person['personid'], $stack ) )
				array_push( $stack, $person['personid'] );

            array_push( $array, $person );
			if ( $person['number_of_childs'] ) {
                $tmp = max( array_keys( $array ) );
                $array[$tmp]['other_children'] = array();
				rear_flashback_relatives( $person['personid'], $person['sex'], $cpl, $ccl, $person['number_of_childs'], $stack, $array[$tmp]['other_children'] );
            }
		}
	}
}


function front_flashback_relatives( &$array, $person, $cpl = 0, &$stack )
{
	static $lpl = 0, $exception = 0;
	if( !in_array( $exception, $stack ) )
		array_push( $stack, $exception );	
		
	//For parents view only
	if( $cpl && NULL != $person ) {
		$relation = get_relation_string( $person['sex'] , $cpl );
        echo "<p class='Debug2'>L:[0][".$cpl."]|ID:".$person['personid']."|".$relation."|=> ".$person['name']." ".$person['surname']."</p>";
		//dump( $stack, "stack" );
	} else {
        $array = $person;
    }
    $array['other_children'] = array();
    rear_flashback_relatives( $person['personid'], $person['sex'], $cpl, 0, $person['number_of_childs'], $stack, $array['other_children'] );

	$lpl = $cpl;
	$exception = $person['personid'];
	
	if( $person['motherid'] || $person['fatherid'] )
	{
		$root = get_parrents( $person['motherid'] ? $person['motherid'] : 0 , $person['fatherid'] ? $person['fatherid'] : 0 );
        $tmp = count( $root );
		$array[ 'motherid' ] = $root[ 0 ];
		$array[ 'fatherid' ] = $root[ 1 ];
		
		++$cpl;
		front_flashback_relatives( $array[ 'motherid' ] , $root[ 0 ] , $cpl , $stack); //Αριστερό υποδένρο.
		//--$cpl;
		
		//++$cpl;
		front_flashback_relatives( $array[ 'fatherid' ] , $root[ 1 ] , $cpl , $stack); //Δεξί υποδένρο.
		--$cpl;
		
	}
}


function create_array( &$array , $person , $lpl , $cpl )
{
	//static $tmp = 0;
	 
	//if(! $cpl) 
		//$array[ $person['personid'] ] = NULL;
	
	//print_r( $tmp );
		
	//$array[] =  $person;
	
	$relation = get_relation_string( $person['sex'] , $cpl );
	echo "<p class='Debug2'>L:[0][".$cpl."]|ID:".$person['personid']."|".$relation."|=> ".$person['name']." ".$person['surname']."</p>";

	//$tmp = &$array[ $person['personid'] ];
}
?>