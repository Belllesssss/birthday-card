function showWish() {
  const name = document.getElementById("nameInput").value;
  const wishText = name
    ? `🎉 Happy Birthday, ${name}! 🎂`
    : "🎂 Happy Birthday! 🎉";
  document.getElementById("wish").innerText = wishText;

  // play song
  document.getElementById("song").play();
}

// Confetti celebration
document.getElementById("confettiBtn").addEventListener("click", () => {
  confetti({
    particleCount: 200,
    spread: 100,
    origin: { y: 0.6 }
  });
});
