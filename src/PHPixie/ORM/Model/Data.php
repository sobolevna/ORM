<?php

namespace \PHPixie\ORM\Model;

class Data
{
    protected $documentBuilder;
    
    public function map($data = null)
    {
        return new Data\Data\Map($data);
    }
    
    public function document($data = null)
    {
        $document = $this->documentBuilder()->document();
        return new Data\Data\Document($document, $data);
    }
    
    protected function documentBuilder()
    {
        if($this->documentBuilder === null)
            $this->documentBuilder = $this->buildDocumentBuilder();
        
        return $this->documentBuilder;
    }
    
    protected function buildDocumentBuilder()
    {
        return new Data\Data\Document\Builder();
    }
}