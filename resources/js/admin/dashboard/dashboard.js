const Dashboard = {
    init() {
        this.renderChart();
    },
    renderChart() {
        const ctx = document.getElementById("myChart");

        new Chart(ctx, {
            type: "bar",
            data: {
                labels: ["Tháng 1", "Tháng 2", "Tháng 3"],
                datasets: [
                    {
                        label: "Doanh thu",
                        data: [100, 200, 150],
                    },
                ],
            },
        });
    },
};

document.addEventListener("DOMContentLoaded", () => {
    Dashboard.init();
});
