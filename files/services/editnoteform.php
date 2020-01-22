<?php
include("connection.php");
# Include the Autoloader (see "Libraries" for install instructions)
use Elasticsearch\ClientBuilder;
require '../vendor/autoload.php';
$client = ClientBuilder::create()->build();

$postid=$_REQUEST['postid'];

$searchParams['index'] = 'mongoindex1';
$searchParams['type']  = 'u_endata';

$concatstr="";
$searchParams['body']= '{
	
	"query": {
		"filtered" : {
            		"filter" : {
				 	"bool" : {
                   	 			"must" : [
                    					{ "term" : { "sno":"'.$postid.'" } } 
                    				]
                			}
            		}
		}
	}
}';
$retDoc = $client->search($searchParams);
if($retDoc['hits']['total']>=1)
{
	$results=$retDoc['hits']['hits'];
}
$mincount = count($results);
if(isset($results))
{
	foreach($results as $r)
	{
		$sno = $r['_source']['sno'];
		$note = $r['_source']['note'];
		$noteflag = $r['_source']['noteflag'];
	}
}

?>

<input type="hidden" name="postid" id="postid" value="<?php echo $sno; ?>">

<fieldset style="padding:10px;">
	<legend>
		<?php 
			if($noteflag=='1')
			{
		?>
				Edit Note
		<?php } 
			else
			{
		?>
				Add Note
		<?php } ?>
	</legend>
	<?php 
	if($noteflag=='1')
	{
	?>
	<textarea name="editnote" id="editnote" style="resize:none;"><?php echo $note;?></textarea>
	<?php } else { ?>
	<textarea name="editnote" id="editnote"></textarea>
	<?php } ?>
	<div align="center"><br>
		<input type="button" class="btn btn-success" name="save" id="save" value="Save" onclick="savenote('<?php echo $sno; ?>')">
	</div>
</fieldset>

