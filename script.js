// Envelope Open + Play Song
const envelope = document.getElementById("envelope");
const song = document.getElementById("song");

envelope.addEventListener("click", () => {
  envelope.classList.toggle("open");
  song.play();
});

// Confetti Celebration
document.getElementById("confettiBtn").addEventListener("click", () => {
  confetti({
    particleCount: 200,
    spread: 120,
    origin: { y: 0.6 }
  });
});

// Slideshow
let slideIndex = 0;
showSlides();
function showSlides() {
  let slides = document.getElementsByClassName("slide");
  for (let i = 0; i < slides.length; i++) {
    slides[i].style.display = "none";
  }
  slideIndex++;
  if (slideIndex > slides.length) { slideIndex = 1; }
  slides[slideIndex-1].style.display = "block";
  setTimeout(showSlides, 4000); // 4s per slide
}

// Balloons (same as before)
const canvas = document.getElementById("balloonCanvas");
const ctx = canvas.getContext("2d");

let balloons = [];
function resizeCanvas() {
  canvas.width = window.innerWidth;
  canvas.height = window.innerHeight;
}
resizeCanvas();
window.addEventListener("resize", resizeCanvas);

class Balloon {
  constructor() {
    this.x = Math.random() * canvas.width;
    this.y = canvas.height + 50;
    this.size = 30 + Math.random() * 30;
    this.speed = 1 + Math.random() * 2;
    this.color = `hsl(${Math.random() * 360}, 80%, 60%)`;
  }
  draw() {
    ctx.beginPath();
    ctx.ellipse(this.x, this.y, this.size * 0.6, this.size, 0, 0, Math.PI * 2);
    ctx.fillStyle = this.color;
    ctx.fill();
    ctx.closePath();

    ctx.beginPath();
    ctx.moveTo(this.x, this.y + this.size);
    ctx.lineTo(this.x, this.y + this.size + 40);
    ctx.strokeStyle = "#555";
    ctx.stroke();
  }
  update() {
    this.y -= this.speed;
    if (this.y + this.size < 0) {
      this.y = canvas.height + 50;
      this.x = Math.random() * canvas.width;
    }
    this.draw();
  }
}
function initBalloons() {
  balloons = [];
  for (let i = 0; i < 25; i++) {
    balloons.push(new Balloon());
  }
}
function animate() {
  ctx.clearRect(0, 0, canvas.width, canvas.height);
  balloons.forEach(balloon => balloon.update());
  requestAnimationFrame(animate);
}
initBalloons();
animate();
