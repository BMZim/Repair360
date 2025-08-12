function submitReview() {
  const name = document.getElementById("reviewer").value.trim();
  const rating = parseInt(document.getElementById("rating").value);
  const comment = document.getElementById("comment").value.trim();
  const reviewList = document.getElementById("reviewList");

  if (!name || !comment) {
    alert("Please fill out all fields.");
    return;
  }

  let stars = "";
  for (let i = 0; i < 5; i++) {
    stars += i < rating ? "★" : "☆";
  }

  const newReview = document.createElement("div");
  newReview.className = "review-item";
  newReview.innerHTML = `<div class="stars">${stars}</div>
    <p><strong>${name}:</strong> ${comment}</p>`;

  reviewList.prepend(newReview);

  // Reset form
  document.getElementById("reviewer").value = "";
  document.getElementById("comment").value = "";
  document.getElementById("rating").value = "5";
}
