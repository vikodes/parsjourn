<?php

class EmailParser
{
    protected $string;

    public function __construct($string)
    {
        $this->string = $string;
    }

    public function pdf2text()
    {
        $parser = new \Smalot\PdfParser\Parser();
        $pdf = $parser->parseContent($this->string);
        $text = $pdf->getText();

        return $text;
    }

    protected function matchEmails($text)
    {
        $pattern = '/[a-z\d._%+-]+@[a-z\d.-]+\.[a-z]{2,4}\b/i';
        preg_match_all($pattern, $text, $matches);

        if (empty($matches[0])) {
            return false;
        }

        return $matches[0];
    }

    public function parseEmails()
    {
        try {
            $text = $this->pdf2text();
        } catch (\Exception $ex) {
            // this is not pdf
            $text = $this->string;
        }

        $emails = $this->matchEmails($text);

        return $emails;
    }
}