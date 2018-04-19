<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Generate Tiny URL</title>

    <!-- Bootstrap core CSS -->
    <link href="<?php echo ASSETS ?>bootstrap/css/bootstrap.min.css" rel="stylesheet">

  </head>

  <body>
    <nav class="navbar navbar-dark sticky-top bg-dark flex-md-nowrap p-0">
      <a class="navbar-brand col-sm-3 col-md-2 mr-0" href="#">Company name</a>
      <input class="form-control form-control-dark w-50" type="text" placeholder="Search" aria-label="Search">
      <ul class="navbar-nav px-3">
      </ul>
    </nav>

    <div class="container-fluid" ng-app="tinyUrlApp" ng-init="initGetPopular()" ng-controller="tinyUrlCtrl" ng-cloak>
      <div class="row">
        <nav class="col-md-2 d-none d-md-block bg-light sidebar">
          <div class="sidebar-sticky">
            <ul class="nav flex-column">
              <li class="nav-item">
                <a class="nav-link active" href="#">
                  <span data-feather="home"></span>
                  Home <span class="sr-only">(current)</span>
                </a>
              </li>
            </ul>

            <h6 class="sidebar-heading d-flex justify-content-between align-items-center px-3 mt-4 mb-1 text-muted">
              <span>Create Tiny URL</span>
              <a class="d-flex align-items-center text-muted" href="#">
                <span data-feather="plus-circle"></span>
              </a>
            </h6>
            <ul class="nav flex-column mb-2">
              <li class="nav-item">
                <a class="nav-link" href="#" ng-model="blockGenUrl" ng-init="blockGenUrl=true" ng-click="blockGenUrl= ! blockGenUrl; blockShowUrl= ! blockShowUrl">
                  <span data-feather="file-text"></span>
                  Get Tiny
                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="#" ng-model="blockShowUrl" ng-init="blockShowUrl = false" ng-click="blockShowUrl= ! blockShowUrl; blockGenUrl= ! blockGenUrl">
                  <span data-feather="file-text"></span>
                  Show Tiny
                </a>
              </li>
            </ul>
          </div>
        </nav>

        <main role="main" class="col-md-9 ml-sm-auto col-lg-10 pt-3 px-4">
          <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
            <h1 class="h2">Home</h1>
            <div class="btn-toolbar mb-2 mb-md-0">
              <div class="btn-group mr-2">
                <button class="btn btn-sm btn-outline-secondary">Dummy Share</button>
                <button class="btn btn-sm btn-outline-secondary">Dummy Export</button>
              </div>
            </div>
          </div>

          <div class="table-responsive" ng-show="blockGenUrl">
          	<h2>Generate Tiny URL</h2>
            <table class="table table-striped table-sm">
              <thead>
                <tr>
                  <th></th>
                  <th></th>
                  <th></th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td>Enter Your Long URL</td>
                  <td><input type="url" ng-model="frm.long_url"></td>
                  <td><button ng-click="getTinyUrl()">Get Tiny</button></td>
                </tr>
                <tr ng-show="frm.is_ok=='success'">
                	<td></td>
                	<td>Tiny URL - {{frm.tiny_url}}</td>
                	<td>Click to Open Tiny URL - <a href="{{frm.tiny_url}}" target="_blank">{{frm.tiny_url}}</a></td>
                </tr>
                <tr ng-show="frm.is_ok=='failure'">
                	<td colspan="3">Error : {{frm.err_msg}}</td>
                </tr>
              </tbody>
            </table>
          </div>

          <div class="table-responsive" ng-show="blockShowUrl">
          	<h2>Most Frequent URLs</h2>
            <table class="table table-striped table-sm">
              <thead>
                <tr>
                  <th>#</th>
                  <th>Tiny URL</th>
                  <th>Access Count</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td>1,001</td>
                  <td>Lorem</td>
                  <td>0</td>
                </tr>
              </tbody>
            </table>
          </div>
        </main>
      </div>
    </div>

    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="<?php echo ASSETS ?>jquery/jquery-3.2.1.slim.min.js"></script>
    <script src="<?php echo ASSETS ?>bootstrap/js/bootstrap.min.js"></script>
    <script src="<?php echo ASSETS ?>angular/js/angular.min.js"></script>
    <script src="<?php echo ASSETS ?>custom/js/tinyUrlApp.js"></script>
  </body>
</html>
