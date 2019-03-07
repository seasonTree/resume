<?php
class Docx2Text
{
    const SEPARATOR_TAB = "\t";

    
    private $docx;

    
    private $domDocument;

    
    private $_document;

    
    private $_numbering;

    
    private $_footnote;

    
    private $_endnote;

    
    private $endnotes;

    
    private $footnotes;

    
    private $relations;

    
    private $numberingList;

    
    private $textOuput;


    
    private $chart2text;

    
    private $table2text;

    
    private $list2text;

    
    private $paragraph2text;

    
    private $footnote2text;

    
    private $endnote2text;

    

    public function __construct($boolTransforms = array())
    {
        
        if (isset($boolTransforms['table'])) {
            $this->table2text = $boolTransforms['table'];
        } else {
            $this->table2text = true;
        }

        if (isset($boolTransforms['list'])) {
            $this->list2text = $boolTransforms['list'];
        } else {
            $this->list2text = true;
        }

        if (isset($boolTransforms['paragraph'])) {
            $this->paragraph2text = $boolTransforms['paragraph'];
        } else {
            $this->paragraph2text = true;
        }

        if (isset($boolTransforms['footnote'])) {
            $this->footnote2text = $boolTransforms['footnote'];
        } else {
            $this->footnote2text = true;
        }

        if (isset($boolTransforms['endnote'])) {
            $this->endnote2text = $boolTransforms['endnote'];
        } else {
            $this->endnote2text = true;
        }

        if (isset($boolTransforms['chart'])) {
            $this->chart2text = $boolTransforms['chart'];
        } else {
            $this->chart2text = true;
        }

        $this->textOuput = '';
        $this->docx = null;
        $this->_numbering = '';
        $this->numberingList = array();
        $this->endnotes = array();
        $this->footnotes = array();
        $this->relations = array();

    }

    

    public function extract($filename = '')
    {
        if (empty($this->_document)) {
            
            exit('There is no content');
        }

        $this->domDocument = new DomDocument();
        $this->domDocument->loadXML($this->_document);
        
        $bodyNode = $this->domDocument->getElementsByTagNameNS('http://schemas.openxmlformats.org/wordprocessingml/2006/main', 'body');
        
        $bodyNode = $bodyNode->item(0);
        foreach ($bodyNode->childNodes as $child) {
            
            if ($this->table2text && $child->tagName == 'w:tbl') {
                
                $this->textOuput .= $this->table($child) . $this->separator();
            } else {
                
                $this->textOuput .= $this->printWP($child) . ($this->paragraph2text ? $this->separator() : '');
            }
        }
        if (!empty($filename)) {
            $this->writeFile($filename, $this->textOuput);
        } else {
            return $this->textOuput;
        }
    }

    
    public function setDocx($filename)
    {
        $this->docx = new ZipArchive();
        $ret = $this->docx->open($filename);
        if ($ret === true) {
            $this->_document = $this->docx->getFromName('word/document.xml');
        } else {
            exit('failed');
        }
    }

    
    private function loadEndNote()
    {
        if (empty($this->endnotes)) {
            if (empty($this->_endnote)) {
                $this->_endnote = $this->docx->getFromName('word/endnotes.xml');
            }
            if (!empty($this->_endnote)) {
                $domDocument = new DomDocument();
                $domDocument->loadXML($this->_endnote);
                $endnotes = $domDocument->getElementsByTagNameNS('http://schemas.openxmlformats.org/wordprocessingml/2006/main', 'endnote');
                foreach ($endnotes as $endnote) {
                    $xml = $endnote->ownerDocument->saveXML($endnote);
                    $this->endnotes[$endnote->getAttribute('w:id')] = trim(strip_tags($xml));
                }
            }
        }
    }

    
    private function loadFootNote()
    {
        if (empty($this->footnotes)) {
            if (empty($this->_footnote)) {
                $this->_footnote = $this->docx->getFromName('word/footnotes.xml');
            }
            if (!empty($this->_footnote)) {
                $domDocument = new DomDocument();
                $domDocument->loadXML($this->_footnote);
                $footnotes = $domDocument->getElementsByTagNameNS('http://schemas.openxmlformats.org/wordprocessingml/2006/main', 'footnote');
                foreach ($footnotes as $footnote) {
                    $xml = $footnote->ownerDocument->saveXML($footnote);
                    $this->footnotes[$footnote->getAttribute('w:id')] = trim(strip_tags($xml));
                }
            }
        }
    }

    
    private function listNumbering()
    {
        $ids = array();
        $nums = array();
        
        $this->_numbering = $this->docx->getFromName('word/numbering.xml');
        if (!empty($this->_numbering)) {
            
            $domDocument = new DomDocument();
            $domDocument->loadXML($this->_numbering);
            $numberings = $domDocument->getElementsByTagNameNS('http://schemas.openxmlformats.org/wordprocessingml/2006/main', 'numbering');
            
            $numberings = $numberings->item(0);
            foreach ($numberings->childNodes as $child) {
                $flag = true;
                foreach ($child->childNodes as $son) {
                    if ($child->tagName == 'w:abstractNum' && $son->tagName == 'w:lvl') {
                        foreach ($son->childNodes as $daughter) {
                            if ($daughter->tagName == 'w:numFmt' && $flag) {
                                $nums[$child->getAttribute('w:abstractNumId')] = $daughter->getAttribute('w:val');
                                $flag = false;
                            }
                        }
                    } elseif ($child->tagName == 'w:num' && $son->tagName == 'w:abstractNumId') {
                        $ids[$son->getAttribute('w:val')] = $child->getAttribute('w:numId');
                    }
                }
            }
            
            foreach ($ids as $ind => $id) {
                if ($nums[$ind] == 'decimal') {
                    
                    $this->numberingList[$id][0] = range(1, 10);
                    $this->numberingList[$id][1] = range(1, 10);
                    $this->numberingList[$id][2] = range(1, 10);
                    $this->numberingList[$id][3] = range(1, 10);
                } else {
                    
                    $this->numberingList[$id][0] = array('*', '*', '*', '*', '*', '*', '*', '*', '*', '*', '*', '*', '*', '*', '*', '*', '*');
                    $this->numberingList[$id][1] = array(chr(175), chr(175), chr(175), chr(175), chr(175), chr(175), chr(175), chr(175), chr(175), chr(175), chr(175), chr(175));
                    $this->numberingList[$id][2] = array(chr(237), chr(237), chr(237), chr(237), chr(237), chr(237), chr(237), chr(237), chr(237), chr(237), chr(237), chr(237));
                    $this->numberingList[$id][3] = array(chr(248), chr(248), chr(248), chr(248), chr(248), chr(248), chr(248), chr(248), chr(248), chr(248), chr(248));
                }
            }
        }
    }

    
    private function printWP($node)
    {
        $ilvl = $numId = -1;
        if ($this->list2text) {
            if (empty($this->numberingList)) {
                $this->listNumbering();
            }
            
            $xpath = new DOMXPath($this->domDocument);
            $query = 'w:pPr/w:numPr';
            $xmlLists = $xpath->query($query, $node);
            $xmlLists = $xmlLists->item(0);

            
            
            
            
            
            
            
            
            
            
            
            
            
            
            
            
            
            
            
            
            
            
            
            
            $ret = $this->toText($node);
            
        } else {
            
            $ret = $this->toText($node);
        }


        
        if ($this->chart2text) {
            $query = 'w:r/w:drawing/wp:inline';
            $xmlChart = $xpath->query($query, $node);
            
            foreach ($xmlChart as $chart) {
                foreach ($chart->childNodes as $child) {
                    foreach ($child->childNodes as $child2) {
                        foreach ($child2->childNodes as $child3) {
                            $rid = $child3->getAttribute('r:id');
                        }
                    }
                }
            }
            
            
            
            
            
            
            
            
            
            
            
            
        }
        
        if ($this->endnote2text) {
            if (empty($this->endnotes)) {
                $this->loadEndNote();
            }
            $query = 'w:r/w:endnoteReference';
            $xmlEndNote = $xpath->query($query, $node);
            foreach ($xmlEndNote as $note) {
                $ret .= '[' . $this->endnotes[$note->getAttribute('w:id')] . '] ';
            }
        }
        
        if ($this->footnote2text) {
            if (empty($this->footnotes)) {
                $this->loadFootNote();
            }
            $query = 'w:r/w:footnoteReference';
            $xmlFootNote = $xpath->query($query, $node);
            foreach ($xmlFootNote as $note) {
                $ret .= '[' . $this->footnotes[$note->getAttribute('w:id')] . '] ';
            }
        }
        if ((($ilvl != -1) && ($numId != -1)) || (1)) {
            $ret .= $this->separator();
        }

        return $ret;
    }

    
    private function separator()
    {
        return "\r\n";
    }

    
    private function table($node)
    {
        $output = '';
        if ($node->hasChildNodes()) {
            foreach ($node->childNodes as $child) {
                
                if ($child->tagName == 'w:tr') {
                    foreach ($child->childNodes as $cell) {
                        
                        if ($cell->tagName == 'w:tc') {
                            if ($cell->hasChildNodes()) {
                                
                                foreach ($cell->childNodes as $p) {
                                    $output .= $this->printWP($p);
                                }
                                $output .= self::SEPARATOR_TAB;
                            }
                        }
                    }
                }
                $output .= $this->separator();
            }
        }
        return $output;
    }


    
    private function toText($node)
    {
        $xml = $node->ownerDocument->saveXML($node);
        return trim(strip_tags($xml));
    }
}