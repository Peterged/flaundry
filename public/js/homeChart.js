const ctx = document.querySelector(".sample-chart").getContext("2d");

console.log(window.location.origin + "/laundry/api/users/robots");
fetch(window.location.origin + "/laundry/api/users/robots", {
  method: "GET",
  headers: {
    "Content-Type": "application/json",
  },
})
  .then((response) => response.json())
  .then((data) => {
    console.log(data);

    const today = new Date();
    const currentDay = today.getDay();

    const first = today.getDate() - today.getDay() + 1;
    const last = first + 6;

    today.setDate(today.getDate() + 1);

    const dateArray = new Array(7)
      .fill(0)
      .map((_, i) => {
        const d = new Date(today);
        d.setDate(last - i);
        return d;
      })
      .reverse()
      .map((d) =>
        d.toLocaleDateString("id-ID", {
          // dateStyle: 'long',
          weekday: "long",
          month: "short",
          day: "numeric",
        })
      );


    let gradient = ctx.createLinearGradient(0, 0, 0, 400);
    gradient.addColorStop(0, "#4C8EF0");
    gradient.addColorStop(1, "#5AD6F2");

    let chartData = {
        labels: [...dateArray],
        datasets: [
            {
                label: "Jumlah Akun baru setiap hari",
                data: [data.length, 5, 6, 7, 3, 4, 2],
                borderWidth: 1,
                fill: true,
                backgroundColor: gradient,
                borderColor: "#fff",
                pointBackgroundColor: "#fff",
                pointBorderColor: "#4C8EF0",
                pointBorderWidth: 2,
                tension: 0.5,
            },
        ],
    };
    let delayed;
    let chartOptions = {
        scaleStepWidth: 1,
        type: "line",
        data: chartData,
        options: {
            radius: 5,
            hitRadius: 30,
            hoverRadius: 12,
            responsive: true,
            animation: {
                onComplete: () => {
                    delayed = true;
                },
                delay: (context) => {
                    let delay = 0;
                    if(context.type === 'data' && context.mode === 'default' && !delayed) {
                        delay = context.dataIndex * 300 + context.datasetIndex * 100;
                    }
                    return delay;
                },
            },
            scales: {
                y: {
                beginAtZero: true,
                },
            },
            layout: {
                padding: 50,
            },
        }
    }

    new Chart(ctx, chartOptions);
  })
