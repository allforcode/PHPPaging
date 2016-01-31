# PHPPaging

This is a simple PHP class for paing the data source retrieved form database.
The page list is composed of the links of ```First```, ```<```(previous page), ```1 2 3 4```(page numbers), ```>``` (next page) and ```Last```. 
You can specify how many pagers occuring in your page, or showing all of them by default.
 
##How to use: 
...Assuming you have already connect with your databas:

1. In your PHP page, firstly initialize a current page variable by

   ```php 
    $currentPage = isset($_GET['page']) ? $_GET['page'] : 1;
  ```
2. Geting $totalRows by querying ``` SELECT count(*) as 'num' FROM <Your Table Name>;```

  The query will not return the number of row directly, the return value really depends on how does in your code logic. Anyway, finding
  an appropriate way to assign the total number of rows to $totalRows

3. Seting the number of rows you want occuring in each page, for example:
   
  ```php
  $pageSize = 5; 
  ```

4. The next step in PHP file is to query one page data from database:
   
   ```php
    $start = ($currentPage - 1) * $pageSize;
    $sql = "SELECT * FROM `<YourTableName>` order by id desc limit " .$start. "," .$pageSize;
    // executing sql statement ...
   ```
5. Declaring a paging object, passing the 3 prameters, for instance:

  ```php 
   $showContent = new Paging($totalRows, $currentPage, $pageSize);
  ```

6. If you have nore than ten pages, I guess you don't want showing all of the numbers, such as:
   
   ``` First < 1 2 3 4 5 6 7 8 9 10 11 12 ... > Last ```

  It is better to specify how many pager occuring in the list, using: 

  ```php
  $showContent->setAllowedPager(5);
  ```
      
  the result should be look like:
 
    ```First < 6 7 8 9 10 > Last```

7. Final step is to output the page list:
		
  ```php
  $showContent->getPaging();
  ```

**For styling, you can simply change the paging.css file.**

_The class is customized from an online tutorial conducted by Jun Xiang (www.houdunwang.com)_

*@author     Po Dong <pingheng2008@gmail.com>*

*@version    1.0 (31/Jan/2016)*
