import Chart from 'chart.js/auto';

const Dashboard = {
    init() {
        this.renderCharts();
    },
    renderCharts() {
        const dataEl = document.getElementById('chart-data');
        if (!dataEl) return;

        const revenueData = JSON.parse(dataEl.dataset.revenue);
        const salesData = JSON.parse(dataEl.dataset.sales);
        const year = dataEl.dataset.year;

        const labels = Array.from({length: 12}, (_, i) => `Tháng ${i + 1}`);

        // Doanh thu (Bar Chart)
        const revenueCtx = document.getElementById('revenueChart');
        if (revenueCtx) {
            new Chart(revenueCtx, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [{
                        label: `Doanh thu năm ${year} (VNĐ)`,
                        data: revenueData,
                        backgroundColor: 'rgba(79, 70, 229, 0.5)',
                        borderColor: 'rgb(79, 70, 229)',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        y: { beginAtZero: true }
                    }
                }
            });
        }

        // Số lượng sản phẩm bán ra (Line Chart)
        const salesCtx = document.getElementById('salesChart');
        if (salesCtx) {
            new Chart(salesCtx, {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [{
                        label: `Sản phẩm bán ra ${year}`,
                        data: salesData,
                        backgroundColor: 'rgba(16, 185, 129, 0.2)',
                        borderColor: 'rgb(16, 185, 129)',
                        borderWidth: 2,
                        fill: true,
                        tension: 0.3
                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        y: { beginAtZero: true }
                    }
                }
            });
        }
    },
};

document.addEventListener("DOMContentLoaded", () => {
    Dashboard.init();
});
