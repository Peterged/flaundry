// Simulating progress
const progressBar = document.getElementById("myProgressBar");
const currentProgress =
  parseInt(progressBar.getAttribute("aria-valueafter")) ?? 0;

const interval = setInterval(() => {
    if(currentProgress > 100) {
        progressBar.style.backgroundColor = "var(--blue-chefchaouen-9)";
    }
  progressBar.style.width = `${Math.max(0, currentProgress)}%`;
}, 500);
