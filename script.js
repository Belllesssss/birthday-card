function showWish() {
  const name = document.getElementById("nameInput").value;
  const wishText = name
    ? `🎉 Happy Birthday, ${name}! 🎂`
    : "🎂 Happy Birthday! 🎉";
  document.getElementById("wish").innerText = wishText;

  // Play birthday song
  const song = document.getElementById("song");
  song.play();
}

// Confetti Celebration
document.getElementById("confettiBtn").addEventListener("click", () => {
  confetti({
    particleCount: 200,
    spread: 120,
    origin: { y: 0.6 }
  });
});
