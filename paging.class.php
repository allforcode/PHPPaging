<?php
/**
 * This is a simple PHP class for paing the data source retrieved form database, 
 * customizing or changing both class and style files based on your needs.
 * The page list is composed of the links of 'First', '<'(previous page), page numbers, 
 * '>' (next) and 'Last'
 * You can specify pagers occuring in your page, or show all of them by default
 * 
 * How to use (assuming you have already connected with your database):
 * 1) In your PHP page, firstly initialize a current page variable by
 *
 *    $currentPage = isset($_GET['page']) ? $_GET['page'] : 1;
 *
 * 2) Geting $totalRows by querying "SELECT count(*) as 'num' FROM <Your Table Name>";
 *
 *	  The query will not return the number of row directly, the return value really depends 
 *    on how your code logic does. Anyway, at last assign the total number of rows to $totalRows
 *
 * 3) Seting the number of rows you want occuring in each page, for example:
 *
 * 		$pageSize = 5; 
 * 
 * 4) The next step in PHP file is to query one page data from database:
 *  
 *    $start = ($currentPage - 1) * $pageSize;
 *    $sql = "SELECT * FROM `<YourTableName>` order by id desc limit " .$start. "," .$pageSize;
 *		// executing sql statement ...
 *
 * 5) Declaring a paging object, passing the 3 prameters, for instance:
 *
 *    $showPaging = new Paging($totalRows, $currentPage, $pageSize);
 *
 * 6) If you have nore than ten pages, I guess you don't want showing all of the numbers, it could look like that::
 *    First < 1 2 3 4 5 6 7 8 9 10 11 12 ... > Last
 *    At this point, you can specify how many pager occuring in the list, using: 
 *
 *        $showPaging->setAllowedPager(5);
 *
 *    the result should be look like:
 *
 *        First < 6 7 8 9 10 > Last
 *
 * 7) Final step is to utput page list:
 *
 * 		$showPaging->getPaging();
 *
 *
 * 8) For styling, you can simply change the paging.css file.
 *
 * *** The class is customized from an online tutorial conducted by Jun Xiang (www.houdunwang.com)
 *
 * @author     Po Dong <pingheng2008@gmail.com>
 * @version    1.0 (31/Jan/2016)
 *
 */
class Paging {

  //current page index start at 1
  private $currentPage;
	
  //total pages
  private $totalPage;
	
	//total rows retrieved from database
  private $totalRows;
  
	//the number of rows showing in one page
  private $pageSize = 5;
  
	//URL
  private $url;
  
	//the number of pagers in pager list
  private $allowedPager;
	
  //the minimum pager in current page
  private $minPager;
	
  //the maximum pager in current page
  private $maxPager;

  public function __construct($totalRows, $curPage, $pageSize = NULL) {
    $this->currentPage = $curPage;

    //the number of rows showing in one page, 5 by default
    if (!is_null($pageSize)) {
      $this->pageSize = $pageSize;
    }

		$this->totalRows = $totalRows;
		
    $this->totalPage = ceil($this->totalRows / $pageSize);
		
    //save current url without 'page' parameter
    $this->url = $this->getUrl();
		
		//if user do not specify how many pagers showing in the pager list
		//all pagers will be shown
		$this->allowedPager = $this->totalPage;
  }
	
	//set the number of pagers in pager list
  function setAllowedPager($value) {
    $this->allowedPager = $value;
  }
	
	//retrieve current url without 'page' parameter
  private function getUrl() {
    $queryArr = array();

    $uri = filter_input(INPUT_SERVER, 'REQUEST_URI');

    //get uri as a array
    $uriArr = parse_url($uri);

    //get query parameter string
    $queryStr = isset($uriArr['query']) ? $uriArr['query'] : '';

    //get query parameters as a array
    parse_str($queryStr, $queryArr);

    //remove 'page' parameter if it is set
    if (isset($queryArr['page'])) {
      unset($queryArr['page']);
    }

    //rebuild the uri and return 
    $returnUri = $uriArr['path'] . '?' . http_build_query($queryArr);

    return $returnUri;
  }
	
	//output pagers, you can change style by selecting '.paging' class
  function getPaging() {
		//if the total number of rows great than page size
		if($this->totalRows > $this->pageSize) {
			print '<div class="paging">';
			print '<ul>';
			print $this->first() . $this->prev() . $this->pageList() . $this->next() . $this->end();
			print '</ul>';
			print '</div>';
		}
  }
	
	//output the pager list range
  private function pageList() {
    //get pager range
    $this->getPagerRange();

    //loop specified ranged pager list
		//there is no link for current page number
    for ($i = $this->minPager; $i <= $this->maxPager; $i++) {
      if ($i == $this->currentPage) {
        print '<li class="current-page">' . $i . '</li>';
      } else {
        print '<li><a href="' . $this->url . '&page=' . $i . '">' . $i . '</a></li>';
      }
    }
  }

  //calculate pager range
	private function getPagerRange() {

		if (($this->totalPage - $this->currentPage) < ceil($this->allowedPager / 2)) {
      $this->minPager = $this->totalPage - $this->allowedPager + 1;
    } else {
      $this->minPager = $this->currentPage - floor($this->allowedPager / 2);
    }

    //if minpager less than zero, enforce it to zero
    $this->minPager = $this->minPager > 0 ? $this->minPager : 1;

    //set maxPager based on minPager
    $this->maxPager = $this->minPager + $this->allowedPager - 1;

    //if maxPager great than $totalPage, enforce it to $totalPage
    $this->maxPager = $this->maxPager <= $this->totalPage ? $this->maxPager : $this->totalPage;
  }

	//output next page '>' link
  private function next() {
    if ($this->currentPage < $this->totalPage) {
      print '<li><a href="' . $this->url . '&page=' . ($this->currentPage + 1) . '">&gt;</a></li>';
    }
  }

  //output previous page '<' link
  private function prev() {
    if ($this->currentPage > 1) {
      print '<li><a href="' . $this->url . '&page=' . ($this->currentPage - 1) . '">&lt;</a></li>';
    }
  }

  //output 'First' link
  private function first() {
    if ($this->currentPage > 1) {
      print '<li><a href="' . $this->url . '&page=1">First</a></li>';
    }
  }

	//output 'Last' link
  private function end() {
    if ($this->currentPage < $this->totalPage) {
      print '<li><a href="' . $this->url . '&page=' . $this->totalPage . '">Last</a></li>';
    }
  }
}