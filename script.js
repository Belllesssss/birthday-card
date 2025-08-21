const heartSeal = document.getElementById("heartSeal");
const envelope = document.getElementById("envelope");

heartSeal.addEventListener("click", (e) => {
  // burst mini hearts
  for (let i = 0; i < 12; i++) {
    createMiniHeart(e.clientX, e.clientY);
  }

  // after 1 second, open the envelope
  setTimeout(() => {
    envelope.classList.add("open");
  }, 1000);
});

function createMiniHeart(x, y) {
  const heart = document.createElement("div");
  heart.classList.add("mini-heart");
  heart.textContent = "💕";

  heart.style.left = (x + (Math.random() * 60 - 30)) + "px";
  heart.style.top = (y + (Math.random() * 60 - 30)) + "px";

  document.body.appendChild(heart);

  setTimeout(() => {
    heart.remove();
  }, 2000);
}
