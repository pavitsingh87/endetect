/* License Activation*/
var app = angular.module('submitExample');
app.controller('lic_customersCrtl', function ($scope, $http, $timeout,$modal,$filter) {
	$scope.availablelicense = 0;
	$scope.pendingactivations = 0;
	$scope.successcount =0;
	$scope.Failurecount=0;
	$scope.pendingusers=null;
	$scope.buttondisabled=true;
	$scope.authandterms = true;
	$scope.alerterror = true;
	$scope.currentPage = 1; //current page
    $scope.entryLimit = 10; //max no of items to display in a page
	$scope.chkcount=0;
	var dataString 	= "mode=liccount";
	var responsePromise= $http.get("services/licenseactivation.php?"+dataString).then(function(response){
		$scope.availablelicense = response.data;
		//console.log(response.data);
		//console.log(response);
	});

	$http.get('services/licenseactivation.php?mode=releaseuser').success(function(data){
		//console.log(data);
		$scope.releasearr =data;
		angular.forEach($scope.releasearr, function (item) {
			item.selected=0;
		});

	});
	$http.get('services/licenseactivation.php?mode=totalcount').success(function(data){
		$scope.totalitems=data;
		//console.log(data);
		$scope.filteredItems = data; //Initially for no filter
        $scope.totalItems = data;
		$scope.pendingactivations = data;
	});

	$http.get('services/licenseactivation.php?page='+$scope.currentPage+'&entrylimit='+$scope.entryLimit).success(function(data) {
		//console.log(data);
        $scope.list = data;
		$scope.selectedAll=false;
    });
    $scope.removeCurrentRelease = function(a, b)
    {
    	//console.log(a);
    	//console.log(b);
    	var userIndex =$scope.releasearr.indexOf(a);
    	//console.log(userIndex);
    	$scope.releasearr[userIndex].selected=0;
    	$scope.filtered[b].selectedItem={"sno":"0","name":"New User","selected":0};
    };
    $scope.updateList = function(a, b)
    {
    	var userIndex =$scope.releasearr.indexOf(a);
    	//console.log(userIndex);
    	if(userIndex!=0)
    	{
	    	angular.forEach($scope.releasearr, function (item) {
	    		var selected=0;
	    		angular.forEach($scope.filtered, function (item1) {
	    			if(item1.selectedItem && item1.selectedItem.selected==1 && item1.selectedItem.sno==item.sno)
	    			{
	    				selected=1;
	    			}
	    		});
	    		if(selected==0)
	    		{
	    			item.selected=0;
	    		}
	    	});
	    	$scope.releasearr[userIndex].selected=1;

	    }

    	/*
    	if(userIndex!="0")
    	{
    		$scope.releasearr.splice(userIndex,1);
    	}*/

    };
	$scope.setPage = function(pageNo) {
		$scope.currentPage = pageNo;
		$http.get('services/licenseactivation.php?page='+$scope.currentPage+'&entrylimit='+$scope.entryLimit).success(function(data) {
			$scope.list = data;
		});
    	var currentpage =  parseInt($scope.currentPage);
        var limit = parseInt($scope.entryLimit);
        var startid = parseInt((currentpage*limit)-limit);
        var lastid = currentpage*limit;
        var j;
        var totalit = parseInt($scope.totalItems);
        if ($scope.selectedAll) {
    	      $scope.selectedAll = false;
    	  } else {
    	      $scope.selectedAll = true;
    	  }
        for(i=0;i<totalit;i++)
    	{
        	$scope.list[i].Selected=false;
    	}
        $scope.selectedAll=false;

    };
	$scope.limitchange=function(limitstr)
	{
		$scope.currentPage = '1';
		$scope.entryLimit=limitstr;
		$http.get('services/licenseactivation.php?page='+$scope.currentPage+'&entrylimit='+$scope.entryLimit).success(function(data) {
			$scope.list = data;
			//console.log(data);
		});
		$scope.selectedAll=false;
	};
	$scope.checkAll = function()
    {
		$scope.chkcount=0;
		var currentpage =  parseInt($scope.currentPage);
		var limit = parseInt($scope.entryLimit);
		var startid = parseInt((currentpage*limit)-limit);
		var lastid = currentpage*limit;
		if ($scope.selectedAll) {
			  $scope.selectedAll = false;


		  } else {
			  $scope.selectedAll = true;
		}

		angular.forEach($scope.list, function (item) {
            item.Selected = $scope.selectedAll;
			if($scope.selectedAll==false)
			{
				if($scope.chkcount>0)
				{
				$scope.chkcount -=1;
				}
				 if((document.getElementById('chklicauth').checked == true) && (document.getElementById('chkterms').checked==true)  && ($scope.chkcount > 0))
			   {
			   $scope.authandterms=false;
			   }
				else
			   {
			   $scope.authandterms=true;
			   }
			}
			else{
				$scope.chkcount +=1;
				 if((document.getElementById('chklicauth').checked == true) && (document.getElementById('chkterms').checked==true)  && ($scope.chkcount > 0))
			   {
			   $scope.authandterms=false;
			   }
				else
			   {
			   $scope.authandterms=true;
			   }
			}
			//console.log($scope.chkcount);
        });
		if(($scope.authandterms==true) && ($scope.chkcount > 0))
	   {
	   $scope.alerterror = false;
	   }
	 else
	   {
	   $scope.alerterror = true;
	   }

    };
	$scope.filter = function() {
        $timeout(function() {
			//console.log("services/licenseactivation.php?mode=filter&searchitem="+$scope.search+"&page="+$scope.currentPage+"&entrylimit="+$scope.entryLimit);
			$http.get("services/licenseactivation.php?mode=filter&searchitem="+$scope.search+"&page="+$scope.currentPage+"&entrylimit="+$scope.entryLimit).success(function(response){
				//console.log(response);
				$scope.list=response;
			});
        }, 10);
    };
    $scope.sort_by = function(predicate) {
        $scope.predicate = predicate;
        $scope.reverse = !$scope.reverse;
    };
	$scope.checkcount = function(id,event)
	{
		  var chkid = event.target.id;
		   if(document.getElementById(chkid).checked == true)
			{
			 $scope.chkcount +=1;
			}
		   else
			{
				if($scope.chkcount>0)
				{
					 $scope.chkcount -=1;
				}

			   $scope.selectedAll=false;
			}
			//console.log($scope.chkcount);
		   if((document.getElementById('chklicauth').checked == true) && (document.getElementById('chkterms').checked==true)  && ($scope.chkcount > 0))
			   {
			   $scope.authandterms=false;
			   }
		   else
			   {
			   $scope.authandterms=true;
			   }
		   if(($scope.chkcount < $scope.availablelicense) && ($scope.chkcount !=0)  && $scope.authandterms==true )
			{
			 $scope.buttondisabled=false;

			}
		   else
		   {
			   $scope.buttondisabled=true;

		   }

		    if(($scope.authandterms==true) && ($scope.chkcount > 0))
			   {
			   $scope.alerterror = false;
			   }
		     else
			   {
			   $scope.alerterror = true;
			   }

	};
	$scope.authterms = function()
	{
		    if((document.getElementById('chklicauth').checked == true) && (document.getElementById('chkterms').checked==true) && ($scope.chkcount <= $scope.availablelicense) && ($scope.chkcount >0))
			{
		    	 $scope.authandterms = false;
		    	 $scope.buttondisabled=false;
		    }
		    else
		    {
		    	$scope.authandterms = true;
		    	 $scope.buttondisabled=true;
		    }
			if($scope.chkcount>$scope.availablelicense)
			{
				$scope.warningerrormsg = "<div class='alert alert-warning'><a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>Sorry your activation request could not be processed.<br. /> Your available licenses "+$scope.availablelicense+"</div>";
			}

			if(($scope.buttondisabled==true) && (document.getElementById('chklicauth').checked == true || document.getElementById('chkterms').checked==true))
			   {
				$scope.alerterror = false;
			   }
		     else
			   {
			   $scope.alerterror = true;
			   }


	};

	$scope.ActiveUsers = function()
    {
		     angular.forEach($scope.list, function (data) {
		    	if(data.Selected==true)
		    	{
					//console.log(data.selectedItem);
					//console.log(data.id);
					$http.get('services/edituserdet.php?active=1&enduserid='+data.id+"&mergeid="+data.selectedItem.sno).success(function(data){
						$http.get('services/licenseactivation.php?page='+$scope.currentPage+'&entrylimit='+$scope.entryLimit).success(function(data) {
							$scope.list = data;
							$scope.currentPage = 1; //current page
							$scope.entryLimit = 10; //max no of items to display in a page
							$scope.filteredItems = $scope.list.length; //Initially for no filter
							$scope.totalItems = $scope.list.length;
						});
						$scope.setPage = function(pageNo) {
							$scope.currentPage = pageNo;
						};
						$scope.filter = function() {
							$timeout(function() {
								$scope.filteredItems = $scope.filtered.length;
							}, 10);
						};
						$scope.sort_by = function(predicate) {
							$scope.predicate = predicate;
							$scope.reverse = !$scope.reverse;
						};
					});
				}
			 });
	};

	$scope.DeleteUsers = function()
    {
	     angular.forEach($scope.list, function(data) {
	    	if(data.Selected == true) {
				//console.log(data.selectedItem);
				//console.log(data.id);
				$http.get('services/edituserdet.php?delete=1&enduserid='+data.id+"&mergeid="+data.selectedItem.sno).success(function(data) {
					$http.get('services/licenseactivation.php?page='+$scope.currentPage+'&entrylimit='+$scope.entryLimit).success(function(data) {
						$scope.list = data;
						$scope.currentPage = 1; //current page
						$scope.entryLimit = 10; //max no of items to display in a page
						$scope.filteredItems = $scope.list.length; //Initially for no filter
						$scope.totalItems = $scope.list.length;
					});
					$scope.setPage = function(pageNo) {
						$scope.currentPage = pageNo;
					};
					$scope.filter = function() {
						$timeout(function() {
							$scope.filteredItems = $scope.filtered.length;
						}, 10);
					};
					$scope.sort_by = function(predicate) {
						$scope.predicate = predicate;
						$scope.reverse = !$scope.reverse;
					};
				});
			}
		 });
	};

});
app.filter('to_trusted', ['$sce', function($sce){
        return function(text) {
            return $sce.trustAsHtml(text);
        };
    }]);
