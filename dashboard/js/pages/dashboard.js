
const maleMonthlyTotals = JSON.parse(localStorage.getItem('maleMonthlyTotals')) || [];
const femaleMonthlyTotals = JSON.parse(localStorage.getItem('femaleMonthlyTotals')) || [];

console.log('Male Monthly Totals from LocalStorage:', maleMonthlyTotals);
console.log('Female Monthly Totals from LocalStorage:', femaleMonthlyTotals);

let customer_options = {
    series: [{
        name: "Male",
        data: maleMonthlyTotals
    }, {
        name: "Female",
        data: femaleMonthlyTotals
    }],
    colors: ['#035392', '#FFCC27'],
    chart: {
        height: 300,
        type: 'line',
    },
    dataLabels: {
        enabled: false
    },
    stroke: {
        curve: 'smooth'
    },
    xaxis: {
        categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
    },
};

let customer_chart = new ApexCharts(document.querySelector("#customer-chart"), customer_options);
customer_chart.render();
