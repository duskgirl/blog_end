$(function() {
  // 区域滚动:iScroll.js;
  var myScroll;
  myScroll = new IScroll('#wrapper', {
    probeType: 2,
    mouseWheel: true
  });
  var bar = echarts.init(document.getElementById('bar'));
  bar.showLoading();
  $.get('/admin/api/visit.php').done(function(data) {
    bar.hideLoading();
    var xdata = [];
    var ydata = [];
    if (data !== null) {
      for (var i = 0; i < data.length; i++) {
        xdata.push(data[i]['created']);
        ydata.push(data[i]['num']);
      }
    }
    bar.setOption({
      title: {
        text: '大思考最近7天网站访问量'
      },
      tooltip: {},
      xAxis: {
        data: xdata
      },
      yAxis: {},
      series: [{
        name: '访问量',
        type: 'bar',
        data: ydata
      }]
    });
  });
})