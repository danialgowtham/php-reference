
<!--        <div class="slim-pageheader">
          <ol class="breadcrumb slim-breadcrumb">
            <li class="breadcrumb-item"><a href="#">Home</a></li>
            <li class="breadcrumb-item active" aria-current="page">Dashboard</li>
          </ol>
          <h6 class="slim-pagetitle">Welcome <?php echo $username['Employee']['first_name'] ?></h6>
        </div> slim-pageheader -->
<div class="report-summary-header">
    <div>
        <h4 class="tx-inverse mg-b-3">Overall KRA Summary</h4>
        <p class="mg-b-0"><i class="icon ion-calendar mg-r-3"></i> <?php echo date('F d, Y', strtotime('01-04-2018')) ?> - <?php echo date('F d, Y'); ?></p>
    </div>
    <div>
            <a href="#" id="overall_rating" class="btn btn-secondary"><i class="icon ion-ios-clock-outline tx-22"></i> Overall Rating</a>
        <a href="<?php echo BASE_PATH; ?>kra_masters/employee_index" class="btn btn-secondary"><i class="icon ion-ios-clock-outline tx-22"></i> Your KRA</a>
        <a href="<?php echo BASE_PATH; ?>kra_masters/reportees_kra" class="btn btn-secondary"><i class="icon ion-ios-gear-outline tx-24"></i> Reportees KRA</a>
    </div>
</div><!-- d-flex -->
<div class="row row-sm">
          <div class="col-lg-4">
            <div class="card card-sales">
              <h6 class="slim-card-title tx-primary">Delivery Report</h6>
              <div class="row">
                <div class="col">
                  <label class="tx-12">Employee</label>
                  <p>213</p>
                </div><!-- col -->
                <div class="col">
                  <label class="tx-12">Submitted</label>
                  <p>190</p>
                </div><!-- col -->
                <div class="col">
                  <label class="tx-12">Reviewed</label>
                  <p>186</p>
                </div><!-- col -->
              </div><!-- row -->

              <div class="progress mg-b-5">
                <div class="progress-bar bg-primary wd-50p" role="progressbar" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100">50%</div>
              </div>
              <p class="tx-12 mg-b-0">Maecenas tempus, tellus eget conditum rhon.</p>
            </div><!-- card -->
          </div><!-- col-4 -->
          <div class="col-lg-4 mg-t-20 mg-lg-t-0">
            <div class="card card-sales">
              <h6 class="slim-card-title tx-success">Enabling Report</h6>
              <div class="row">
                <div class="col">
                  <label class="tx-12">Employees</label>
                  <p>1,263</p>
                </div><!-- col -->
                <div class="col">
                  <label class="tx-12">Submitted</label>
                  <p>28,767</p>
                </div><!-- col -->
                <div class="col">
                  <label class="tx-12">Reviewed</label>
                  <p>68,324</p>
                </div><!-- col -->
              </div><!-- row -->

              <div class="progress mg-b-5">
                <div class="progress-bar bg-success wd-75p" role="progressbar" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100">75%</div>
              </div>
              <p class="tx-12 mg-b-0">Maecenas tempus, tellus eget conditum rhon.</p>
            </div><!-- card -->
          </div><!-- col-4 -->
          <div class="col-lg-4 mg-t-20 mg-lg-t-0">
            <div class="card card-sales">
              <h6 class="slim-card-title tx-danger">Sales Report</h6>
              <div class="row">
                <div class="col">
                  <label class="tx-12">Employees</label>
                  <p>1,263</p>
                </div><!-- col -->
                <div class="col">
                  <label class="tx-12">Submitted</label>
                  <p>28,767</p>
                </div><!-- col -->
                <div class="col">
                  <label class="tx-12">Reviewed</label>
                  <p>68,324</p>
                </div><!-- col -->
              </div><!-- row -->

              <div class="progress mg-b-5">
                <div class="progress-bar bg-danger wd-35p" role="progressbar" aria-valuenow="35" aria-valuemin="0" aria-valuemax="100">35%</div>
              </div>
              <p class="tx-12 mg-b-0">Maecenas tempus, tellus eget conditum rhon.</p>
            </div><!-- card -->
          </div><!-- col-4 -->
        </div>
<div class="card-deck card-deck-sm mg-t-20 mg-x-0">
          <div class="card tx-center">
           <div class="card-body pd-25">
              <div class="slim-card-title">Recommendations</div>
              <div class="media align-items-center mg-y-25">
                <img src="http://via.placeholder.com/500x500" class="wd-40 rounded-circle" alt="">
                <div class="media-body mg-l-15">
                  <h6 class="tx-14 mg-b-2"><a href="">Rolando Paloso</a></h6>
                  <p class="mg-b-0">Head Architect</p>
                </div><!-- media-body -->
              </div><!-- media -->

              <p class="tx-13">Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor. Aenean massa. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus.</p>
              <p class="tx-13 mg-b-0">Donec quam felis, ultricies nec, pellentesque eu, pretium quis, sem. Nulla consequat massa quis enim. Donec pede justo, fringilla vel, aliquet nec, vulputate eget, arcu. In enim justo, rhoncus ut, imperdiet a, venenatis vitae, justo.</p>
            </div>
          </div><!-- card -->
          <div class="card tx-center">
<!--            <div class="card-body pd-40">
               <div class="row">
           
            <div class="col mg-t-20 mg-md-t-0">-->
              <div class=" pd-t-30 pd-b-20 pd-x-20"><canvas id="chartDonut" height="200"></canvas></div>
             <div class="media-body">
                    <h6>KRA Ratings </h6>
                    <p>an hour ago</p>
                  </div>
              <p class="msg">The European languages are members of the same family. Their separate existence is a myth...</p>
<!--            </div> col-6 
          </div> row 
            </div> card-body -->
          </div><!-- card -->
          <div class="card card-recent-messages">
            <div class="card-header">
              <span>Recent Messages</span>
              <a href=""><i class="icon ion-more"></i></a>
            </div><!-- card-header -->
            <div class="list-group list-group-flush">
              <div class="list-group-item">
                <div class="media">
                  <img src="http://via.placeholder.com/500x500" alt="">
                  <div class="media-body">
                    <h6>Katherine Lumaad</h6>
                    <p>an hour ago</p>
                  </div><!-- media-body -->
                </div><!-- media -->
                <p class="msg">The European languages are members of the same family. Their separate existence is a myth...</p>
              </div><!-- list-group-item -->
              <div class="list-group-item">
                <div class="media">
                  <img src="http://via.placeholder.com/500x500" alt="">
                  <div class="media-body">
                    <h6>Mary Grace Ceballos</h6>
                    <p>2 hours ago</p>
                  </div><!-- media-body -->
                </div><!-- media -->
                <p class="msg">The European languages are members of the same family. Their separate existence is a myth...</p>
              </div><!-- list-group-item -->
              <div class="list-group-item">
                <div class="media">
                  <img src="http://via.placeholder.com/500x500" alt="">
                  <div class="media-body">
                    <h6>Rowella Sombrio</h6>
                    <p>3 hours ago</p>
                  </div><!-- media-body -->
                </div><!-- media -->
                <p class="msg">The European languages are members of the same family. Their separate existence is a myth...</p>
              </div><!-- list-group-item -->
            </div><!-- list-group -->
            <div class="card-footer">
              <a href="" class="tx-12"><i class="fa fa-angle-down mg-r-5"></i> Show all messages</a>
            </div><!-- card-footer -->
          </div><!-- card -->
        </div>
<div class = "card-deck card-deck-sm mg-t-20 mg-x-0">
    <div class="card card-carousel">
            <div id="carousel2" class="carousel slide" data-ride="carousel">
              <ol class="carousel-indicators">
                <li data-target="#carousel2" data-slide-to="0" class=""></li>
                <li data-target="#carousel2" data-slide-to="1" class=""></li>
                <li data-target="#carousel2" data-slide-to="2" class="active"></li>
              </ol>
              <div class="carousel-inner" role="listbox">
                <div class="carousel-item">
                  <div class="carousel-item-wrapper bg-primary">
                    <div class="carousel-item-option">
                      <a href=""><i class="icon ion-edit tx-16"></i></a>
                      <a href=""><i class="icon ion-stats-bars tx-20"></i></a>
                      <a href=""><i class="icon ion-gear-a tx-20"></i></a>
                      <a href=""><i class="icon ion-more tx-20"></i></a>
                    </div>
                    <div>
                      <p class="carousel-item-label">Recent Article</p>
                      <h5 class="carousel-item-title">20 Best Travel Tips After 5 Years Of Traveling The World</h5>
                      <nav class="nav flex-row">
                        <a href="">12K+ Views</a>
                        <a href="">234 Shares</a>
                        <a href="">43 Comments</a>
                      </nav>
                    </div>
                  </div><!-- carousel-item-wrapper -->
                </div>
                <div class="carousel-item">
                  <div class="carousel-item-wrapper bg-danger">
                    <div class="carousel-item-option">
                      <a href=""><i class="icon ion-edit tx-16"></i></a>
                      <a href=""><i class="icon ion-stats-bars tx-20"></i></a>
                      <a href=""><i class="icon ion-gear-a tx-20"></i></a>
                      <a href=""><i class="icon ion-more tx-20"></i></a>
                    </div>
                    <div>
                      <p class="carousel-item-label">Recent Article</p>
                      <h5 class="carousel-item-title">How I Flew Around the World in Business Class for $1,340</h5>
                      <nav class="nav flex-row">
                        <a href="">Edit</a>
                        <a href="">Unpublish</a>
                        <a href="">Delete</a>
                      </nav>
                    </div>
                  </div><!-- d-flex -->
                </div>
                <div class="carousel-item active">
                  <div class="carousel-item-wrapper bg-purple">
                    <div class="carousel-item-option">
                      <a href=""><i class="icon ion-edit tx-16"></i></a>
                      <a href=""><i class="icon ion-stats-bars tx-20"></i></a>
                      <a href=""><i class="icon ion-gear-a tx-20"></i></a>
                      <a href=""><i class="icon ion-more tx-20"></i></a>
                    </div>
                    <div>
                      <p class="carousel-item-label">Recent Article</p>
                      <h5 class="carousel-item-title">10 Reasons Why Travel Makes You a Happier Person</h5>
                      <nav class="nav flex-row">
                        <a href="">Edit</a>
                        <a href="">Unpublish</a>
                        <a href="">Delete</a>
                      </nav>
                    </div>
                  </div><!-- d-flex -->
                </div>
              </div><!-- carousel-inner -->
            </div><!-- carousel -->
          </div>
   
    <div class="card card-body pd-25 tx-center">
            <div class="card-icon-wrapper success">
              <i class="icon ion-ios-paper-outline"></i>
            </div><!-- icon-wrapper -->
            <h4 class="tx-gray-800 mg-b-25">Knowledge Base</h4>
            <p class="mg-b-25">Aliquam lorem ante, dapibus in, viverra quis, feugiat a, tellus. Phasellus viverra nulla ut metus varius laoreet. Quisque rutrum.</p>
            <a href="" class="btn btn-success btn-block">Browse Articles</a>
          </div>
    <div class="card card-activities">
            <h6 class="slim-card-title">Recent Activities</h6>
            <p>Last activity was 1 hour ago</p>

            <div class="media-list">
              <div class="media">
                <div class="activity-icon bg-primary">
                  <i class="icon ion-stats-bars"></i>
                </div><!-- activity-icon -->
                <div class="media-body">
                  <h6>Report has been updated</h6>
                  <p>Aenean vulputate eleifend tellus. Aenean leo ligula, porttitor.</p>
                  <span>2 hours ago</span>
                </div><!-- media-body -->
              </div><!-- media -->
              <div class="media">
                <div class="activity-icon bg-success">
                  <i class="icon ion-trophy"></i>
                </div><!-- activity-icon -->
                <div class="media-body">
                  <h6>Achievement Unlocked</h6>
                  <p>Aenean vulputate eleifend tellus. Aenean leo ligula, porttitor.</p>
                  <span>2 hours ago</span>
                </div><!-- media-body -->
              </div><!-- media -->
              <div class="media">
                <div class="activity-icon bg-purple">
                  <i class="icon ion-image"></i>
                </div><!-- activity-icon -->
                <div class="media-body">
                  <h6>Added new images</h6>
                  <p>Aenean vulputate eleifend tellus. Aenean leo ligula, porttitor.</p>
                  <span>2 hours ago</span>
                </div><!-- media-body -->
              </div><!-- media -->
            </div><!-- media-list -->
          </div>
</div>
</div><!-- container -->

<script>
    
    $(function(){
         var randomScalingFactor = function() {
    return Math.round(Math.random() * 100);
  };
         var datapie = {
    datasets: [{
      data: [
        randomScalingFactor(),
        randomScalingFactor(),
        randomScalingFactor(),
        randomScalingFactor(),
        randomScalingFactor(),
      ],
      backgroundColor: [
        '#29B0D0',
        '#4C6579',
        '#F57E2E',
        '#C8E0E4',
        '#A6A7AC'
      ]
    }]
  };
   var optionpie = {
    responsive: true,
    legend: {
      display: false,
    },
    animation: {
      animateScale: true,
      animateRotate: true
    }
  };
        var ctx7 = document.getElementById('chartDonut');
  var myPieChart7 = new Chart(ctx7, {
    type: 'pie',
    data: datapie,
    options: optionpie
  }); 
    });
//    $(function () {
//        'use strict'
//
//
//
//
//        var newCust = [[0, 2], [1, 3], [2, 6], [3, 5], [4, 7], [5, 8], [6, 10]];
//        var retCust = [[0, 1], [1, 2], [2, 5], [3, 3], [4, 5], [5, 6], [6, 9]];
//
//        var plot = $.plot($('#flotArea1'), [
//            {
//                data: newCust,
//                label: 'New Customer',
//                color: '#1B84E7'
//            },
//            {
//                data: retCust,
//                label: 'Returning Customer',
//                color: '#4E6577'
//            }],
//                {
//                    series: {
//                        lines: {
//                            show: true,
//                            lineWidth: 0,
//                            fill: 0.8
//                        },
//                        shadowSize: 0
//                    },
//                    points: {
//                        show: false,
//                    },
//                    legend: {
//                        noColumns: 1,
//                        position: 'nw'
//                    },
//                    grid: {
//                        hoverable: true,
//                        clickable: true,
//                        borderColor: '#ddd',
//                        borderWidth: 0,
//                        labelMargin: 5,
//                        backgroundColor: '#fff'
//                    },
//                    yaxis: {
//                        min: 0,
//                        max: 15,
//                        color: '#eee',
//                        font: {
//                            size: 10,
//                            color: '#999'
//                        }
//                    },
//                    xaxis: {
//                        color: '#eee',
//                        font: {
//                            size: 10,
//                            color: '#999'
//                        }
//                    }
//                });
//
////        $.plot("#flotBar1", [{
////                data: [[1, 0], [2, 8], [3, 5], [4, 13], [5, 5]]
////            }], {
////            series: {
////                bars: {
////                    show: true,
////                    lineWidth: 0,
////                    fillColor: '#4E6577'
////                }
////            },
////            grid: {
////                borderWidth: 1,
////                borderColor: '#D9D9D9'
////            },
////            yaxis: {
////                tickColor: '#d9d9d9',
////                font: {
////                    color: '#666',
////                    size: 10
////                }
////            },
////            xaxis: {
////                tickColor: '#d9d9d9',
////                font: {
////                    color: '#666',
////                    size: 10
////                }
////            }
////        });
//
//        // Donut chart
//        $('.peity-donut').peity('donut');
//
//    });
$(document).ready(function () {
    var newsFeedObj;
    $.getJSON("https://newsapi.org/v2/top-headlines?country=in&category=business&apiKey=65787e2c25714fdbb46af4d7fd19f505",
function(data) {
  $(".news_feeds").html('');
   $.each(data.articles, function( index, article ) {
        console.log(article.title);
           var media = '<div class="media">';
//               media +='<div class="activity-icon bg-primary">';
               media +=' <img src="'+article.urlToImage+'" alt="" class ="wd-55">';
//               media +='     </div><!-- activity-icon -->';
               media +='     <div class="media-body">';
               media +='         <a href ="'+article.url+'"><h6>'+article.title+'</h6><a>';
               media +='         <p>'+article.description+'</p>';
               media +='         <span>2 hours ago</span>';
               media +='     </div><!-- media-body -->';
               media +=' </div><!-- media -->';
                $(".news_feeds").append(media);
                 return index<2;
   });
});
});
</script>

<?php
echo $javascript->link('/kra/lib/Flot/js/jquery.flot.js');
echo $javascript->link('/kra/lib/Flot/js/jquery.flot.resize.js');
echo $javascript->link('/kra/lib/peity/js/jquery.peity.js');
//echo $javascript->link('/kra/js/chart.chartjs.js');
echo $javascript->link('/kra/lib/chart.js/js/Chart.js');
?>