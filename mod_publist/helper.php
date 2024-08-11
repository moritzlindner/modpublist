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
        $fs = $params->get('font-size');
        $short = $params->get('short');
        $url = 'https://eutils.ncbi.nlm.nih.gov/entrez/eutils/efetch.fcgi?db=pubmed&id='.$contents.'&rettype=xml';
        try {
            $xml = simplexml_load_file($url);
            if ($xml === false) {
                $xml = "FAIL";
            }
        } catch (Exception $e) {
            $xml = "FAIL";
        }

        $articles = [];
        if ($xml != "FAIL") {
            foreach ($xml->PubmedArticle as $article) {
                $articles[] = $article;
            }

            // Define custom sorting function
            usort($articles, function ($a, $b) {
                $yearA = (int)$a->MedlineCitation->Article->Journal->JournalIssue->PubDate->Year;
                $yearB = (int)$b->MedlineCitation->Article->Journal->JournalIssue->PubDate->Year;
                return $yearB <=> $yearA; // Sort in ascending order; use $yearB <=> $yearA for descending order
            });
        }

        ob_start(); ?>
            <head>
                <script type='text/javascript' src='https://d1bxh8uas1mnw7.cloudfront.net/assets/embed.js'></script>
            </head>

            <section class="blog">
                <?php 
                    if ($short == 0){
                        echo '<h2>Publications</h2>';                        
                    }
                    if ($xml != "FAIL"){
                ?>            
                <table class="table" style="height: 100%; width: 100%; font-size:<?php echo $fs ?>;" border="1">
                    <tbody>
                    <?php 
                        $idx = 1;
                        $lastyear = 0;
                        foreach($articles as $line) { // Use sorted array instead of XML directly
				$currentYear = (int) $line->MedlineCitation->Article->Journal->JournalIssue->PubDate->Year;
                    		if ($short == 0){
		                    if ($currentYear != $lastyear) {
		                        echo '<tr><td></td><td><h3>'.$line->MedlineCitation->Article->Journal->JournalIssue->PubDate->Year.'</h4></td><td></td></tr>';
		                    }
				}                
                            echo '<tr>';
                            if ($short == 0){
                                echo '<td>'.$idx.'</td>';                        
                            }
                                echo '<td>';
                                $last = 0;
                                $i = 0;
                                $len = count($line->MedlineCitation->Article->AuthorList->Author);
                                if ($short == 0){
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
                               } else {         
                                    $author = $line->MedlineCitation->Article->AuthorList->Author[0];
                                    echo $author->LastName.' '.$author->Initials;
                                    echo ', et al. ';
                                }
                                echo ' ('.$line->MedlineCitation->Article->Journal->JournalIssue->PubDate->Year.')<br>';
                                echo '<a href="http://www.ncbi.nlm.nih.gov/pubmed?term='.$line->MedlineCitation->PMID.'" target="_blank"><b>'.$line->MedlineCitation->Article->ArticleTitle.'</b></a><br>';
                                echo '<i>'.$line->MedlineCitation->Article->Journal->Title.'</i> '.$line->MedlineCitation->Article->Journal->JournalIssue->Volume.'('.$line->MedlineCitation->Article->Journal->JournalIssue->Issue.'): '.$line->MedlineCitation->Article->Pagination->MedlinePgn.'.';
                                echo '</td>';
                                echo '<td style="width: 10px;"><div class=\'altmetric-embed\' data-badge-popover="right" data-badge-type=\'donut\' data-hide-no-mentions="true" data-hide-less-than="3" data-link-target=\'_blank\' data-pmid='.$line->MedlineCitation->PMID.'></div></td>';
                            echo '</tr>';
                            echo '<tr>&nbsp;</tr>';
                            $lastyear = $currentYear;
                            $idx++;
                        }

                    ?>
                    </tbody>
                </table>
                <?php 
                    } else {
                        echo "Loading Pubmed feed failed."; // <-- The missing semicolon was added here
                    }
                ?> 
            </section>

        <?php
        return ob_get_clean();
    }
}
?>

