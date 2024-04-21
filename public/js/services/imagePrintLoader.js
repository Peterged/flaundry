// Get all image elements on the page
const images = document.querySelectorAll("img");

// Create an array of promises for each image to load
const imagePromises = Array.from(images).map((img) => {
  return new Promise((resolve, reject) => {
    const loaded = () => resolve(img);
    const errored = () => reject(new Error(`Failed to load image ${img.src}`));
    img.addEventListener("load", loaded);
    img.addEventListener("error", errored);
  });
});

// Wait for all images to load before printing
Promise.all(imagePromises)
  .then(() => {
    window.print();
  })
  .catch((error) => {
    console.error("Failed to load all images:", error);
  });

  console.log('img')