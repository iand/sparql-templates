<?php
require_once 'lib/Twig-1.5.1/lib/Twig/Autoloader.php';
Twig_Autoloader::register();
require_once 'lib/potassium.php';

class SparqlExtension extends Twig_Extension_Core {
    public function getFilters() {
        return array_merge(parent::getFilters(), array(
            'demo' => new Twig_Filter_Method($this, 'demoFilter'),
            // ...
        ));
    }

    public function demoFilter($string) {
      return 'aaa';

    }
}

class SparqlTemplate {
  
  function SparqlTemplate($query_template, $output_template) {
    $this->query_template = $query_template;
    $this->output_template = $output_template;
    $loader = new Twig_Loader_String();
    $this->twig = new Twig_Environment($loader);
    $this->twig->addExtension(new SparqlExtension());  
  }

  function format_query($params) {
    return $this->twig->render($this->query_template, $params);    
  }


  function execute($params, $service_uri, $api_key) {

    $query = $this->format_query($params);    

    $kasabi = new Potassium($api_key);
    $results = $kasabi->get($service_uri, array('query'=>$query));
    if ($results) {
      return $this->twig->render($this->output_template, array('results' => $results));    
    }
    else {
      $response = $kasabi->last_response();
      print "Failed with response: " . $response->responseCode;
      print "Body: " . $response->body;
      print "Headers: \n";
      print_r($response->headers);
    }    
  }


}