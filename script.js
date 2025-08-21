/***************
 * PERSONALIZE *
 ***************/
let partnerNameFallback = "My Love"; // fallback when no name entered
let letterTemplate = `Happy Birthday, {name}! 💖

On your special day, I just want to remind you how grateful I am for you.
Thank you for your kindness, your laughter, and the way you make everything brighter.

I’m proud of you—today and every day. I pray that your journey is always blessed with joy,
good health, and beautiful surprises. You deserve all the love in the world.

With all my heart,
— From me to you ✨`;

/***************
 * INTERACTIONS
 ***************/
function personalizedName() {
  const name = document.getElementById("nameInput").value.trim();
  return name || partnerNameFallback;
}

// Wish + music
function showWish() {
  const name = personalizedName();
  const wishText = name ? `🎉 Happy Birthday, ${name}! 🎂💖` : "🎂 Happy Birthday! 🎉";
  document.getElementById("wish").innerText = wishText;

  const song = document.getElementById("song");
  song.play();
}

// Confetti
document.getElementById("confettiBtn").addEventListener("click", () => {
  confetti({ particleCount: 200, spread: 120, origin: { y: 0.6 } });
});

// Love Letter panel
const letterBtn   = document.getElementById("letterBtn");
const letterPanel = document.getElementById("letterPanel");
const letterText  = document.getElementById("letterText");
const editToggle  = document.getElementById("editToggle");
const editorWrap  = document.getElementById("editorWrap");
const letterEditor= document.getElementById("letterEditor");
const saveLetter  = document.getElementById("saveLetter");
const cancelEdit  = document.getElementById("cancelEdit");
const printBtn    = document.getElementById("printBtn");

// Typewriter effect
async function typeWriter(text, el, speed = 18) {
  el.textContent = "";
  for (let i = 0; i < text.length; i++) {
    el.textContent += text[i];
    if (i % 7 === 0) sprinkleHearts(el); // subtle hearts while typing
    await new Promise(r => setTimeout(r, speed));
  }
}
function sprinkleHearts(el){
  const heart = document.createElement("span");
  heart.className = "heart";
  heart.textContent = "♥";
  heart.style.left = Math.random()*80 + "%";
  heart.style.top  = Math.random()*8 + "px";
  heart.style.color = `hsl(${Math.random()*360}, 80%, 60%)`;
  el.appendChild(heart);
  setTimeout(()=> heart.remove(), 2000);
}

// Open/close & render letter
letterBtn.addEventListener("click", () => {
  const open = letterPanel.classList.toggle("open");
  letterBtn.textContent = open ? "💌 Close Love Letter" : "💌 Open Love Letter";
  if (open) renderLetter();
});

function renderLetter() {
  const name = personalizedName();
  const letter = letterTemplate.replaceAll("{name}", name);
  typeWriter(letter, letterText);
}

// Edit letter
editToggle.addEventListener("click", () => {
  editorWrap.classList.add("open");
  letterEditor.value = letterTemplate;
  letterEditor.focus();
});
saveLetter.addEventListener("click", () => {
  letterTemplate = letterEditor.value;
  editorWrap.classList.remove("open");
  renderLetter();
});
cancelEdit.addEventListener("click", () => {
  editorWrap.classList.remove("open");
});

// Print only the letter (CSS @media print handles visuals)
printBtn.addEventListener("click", () => window.print());

/********************
 * Balloons Canvas  *
 ********************/
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
    this.reset(true);
  }
  reset(initial=false){
    this.x = Math.random() * canvas.width;
    this.y = initial ? (canvas.height + Math.random()*canvas.height) : (canvas.height + 50);
    this.size = 28 + Math.random() * 34;
    this.speed = 0.6 + Math.random() * 1.6;
    this.sway = Math.random() * 0.8 + 0.2;
    this.angle = Math.random() * Math.PI * 2;
    this.color = `hsl(${Math.random()*360}, 80%, 60%)`;
  }
  draw() {
    // balloon
    ctx.beginPath();
    ctx.ellipse(this.x, this.y, this.size * 0.6, this.size, 0, 0, Math.PI * 2);
    ctx.fillStyle = this.color;
    ctx.shadowColor = this.color;
    ctx.shadowBlur = 16;
    ctx.fill();
    ctx.shadowBlur = 0;
    // knot
    ctx.beginPath();
    ctx.moveTo(this.x, this.y + this.size * 0.9);
    ctx.lineTo(this.x - 3, this.y + this.size);
    ctx.lineTo(this.x + 3, this.y + this.size);
    ctx.closePath();
    ctx.fillStyle = this.color;
    ctx.fill();
    // string
    ctx.beginPath();
    ctx.moveTo(this.x, this.y + this.size);
    ctx.quadraticCurveTo(
      this.x + Math.sin(this.angle)*6,
      this.y + this.size + 20,
      this.x + Math.cos(this.angle)*3,
      this.y + this.size + 45
    );
    ctx.strokeStyle = "rgba(50,50,50,.7)";
    ctx.lineWidth = 1;
    ctx.stroke();
  }
  update() {
    this.angle += 0.02;
    this.y -= this.speed;
    this.x += Math.sin(this.angle) * this.sway;

    if (this.y + this.size < -30) this.reset();
    this.draw();
  }
}
function initBalloons() {
  balloons = [];
  const count = Math.min(30, Math.floor((canvas.width * canvas.height) / 30000));
  for (let i = 0; i < count; i++) balloons.push(new Balloon());
}
function animate() {
  ctx.clearRect(0, 0, canvas.width, canvas.height);
  balloons.forEach(b => b.update());
  requestAnimationFrame(animate);
}
initBalloons();
animate();

/********************
 * Slideshow
 ********************/
let currentSlide = 0;
const slides = document.querySelectorAll(".slideshow .slide");
function showSlides() {
  if (!slides.length) return;
  slides.forEach(s => s.classList.remove("active"));
  slides[currentSlide].classList.add("active");
  currentSlide = (currentSlide + 1) % slides.length;
}
setInterval(showSlides, 3000);
