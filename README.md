# PHPPaging

This is a simple class for showing page list for the data source retrieved form database.
The page list is composed of the links of 'First', '<'(previous page), page numbers, '>' (next) and 'Last'. 
You can specify how many number of pagers occuring in your page, or showing all of them by default
 
How to use (assuming you have already connect with your database):

1. In your PHP page, firstly initialize the currentPage variable by

   ```php 
    $currentPage = isset($_GET['page']) ? $_GET['page'] : 1;
  ```
2. Geting $totalRows by querying ``` SELECT count(*) as 'num' FROM <Your Table Name>;```

  The query will not return the number of row directly, the return value really depends on how does in your code. Anyway, finding
  appropriate way to assign the total number of rows to $totalRows

3. Seting the number of rows you want occuring in your page, for example:
   
  ```php
  $pageSize = 5; 
  ```

4. Declaring a paging object, passing the 3 prameters, for instance:

  ```php 
   $showContent = new PagingContent($totalRows, $currentPage, $pageSize);
  ```

5. If you have nore than ten pages, I guess you don't want showing all of the numbers, such as:
   
   ``` First < 1 2 3 4 5 6 7 8 9 10 11 12 ... > Last ```

  It is better to specify how many pager occuring in the list, using: 

  ```php
  $showContent->setAllowedPager(5);
  ```
      
  the result should be look like:
 
    ```First < 6 7 8 9 10 > Last```

6. Finally, output page list:
		
  ```php
  $showContent->getPagingContent();
  ```

7. For styling, you can simply change the paging.css file.

*** The class is customized from an online tutorial conducted by Jun Xiang (www.houdunwang.com) ***

@author     Po Dong <pingheng2008@gmail.com>

@version    1.0 (31/Jan/2016)
