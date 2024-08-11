<?php
/**
 * Helper class for Publication List module
 * 
 * @package    NULL
 * @subpackage NULL
 * @link docs.joomla.org/J2.5:Creating_a_simple_module/Developing_a_Basic_Module
 * @license        GNU/GPL, see LICENSE.php
 */
class ModpublistHelper
{
    /**
     *
     * @param array $params An object containing the module parameters
     * @access public
     */    
    public static function getpublist( $params )
    {
	$contents = preg_replace('#\s+#',',',trim($params->get('PMIDs')));
  	$url = 'https://eutils.ncbi.nlm.nih.gov/entrez/eutils/efetch.fcgi?db=pubmed&id='.$contents.'&rettype=xml';
	$xml = simplexml_load_file($url) or die("feed not loading");
	ob_start(); ?>
		<head>
			<script type='text/javascript' src='https://d1bxh8uas1mnw7.cloudfront.net/assets/embed.js'></script>
		</head>
        <section class="blog">
            <h2>Publications
			</h2>
            <table class="table" style="height: 100%; width: 100%;" border="1">
                <tbody>
                <?php 
                    foreach($xml->PubmedArticle as $line) {
                        echo '<tr>';
                            echo '<td>';
                            $last = 0;
                            $i = 0;
                            $len = count($line->MedlineCitation->Article->AuthorList->Author);
                            foreach ($line->MedlineCitation->Article->AuthorList->Author as $author){
                                if ($last != 0){
                                    if ($i != $len) {
                                        echo ', ';
                                        }
                                }
                                echo $author->LastName.' '.$author->Initials;
                                $last = 1;
                                $i++;
                            }
                            echo ' ('.$line->MedlineCitation->Article->Journal->JournalIssue->PubDate->Year.')<br>';
                            echo '<b>'.$line->MedlineCitation->Article->ArticleTitle.'</b><br>';
                            echo '<i>'.$line->MedlineCitation->Article->Journal->Title.'</i> '.$line->MedlineCitation->Article->Journal->JournalIssue->Volume.'('.$line->MedlineCitation->Article->Journal->JournalIssue->Issue.'): '.$line->MedlineCitation->Article->Pagination->MedlinePgn.'.';
                            echo ' <a href="http://www.ncbi.nlm.nih.gov/pubmed?term='.$line->MedlineCitation->PMID.'" target="_blank">Pubmed</a></td>';
                            echo '<td style="width: 10px;"><div class=\'altmetric-embed\' data-badge-popover="right" data-badge-type=\'donut\' data-hide-no-mentions="true" data-hide-less-than="3" data-link-target=\'_blank\' data-pmid='.$line->MedlineCitation->PMID.'></div></td>';
                        echo '</tr>';
                        echo '<tr>&nbsp;</tr>';
                    }

                    ?>
                </tbody>
            </table>
        </section>

	<?php
        return ob_get_clean();
    }
}
?>
