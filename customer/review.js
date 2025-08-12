let selectedRating = 0;

document.querySelectorAll('.star').forEach(star => {
  star.addEventListener('click', () => {
    selectedRating = parseInt(star.getAttribute('data-value'));
    updateStars();
  });
});

function updateStars() {
  document.querySelectorAll('.star').forEach(star => {
    const val = parseInt(star.getAttribute('data-value'));
    star.classList.toggle('selected', val <= selectedRating);
  });
}

function submitReview() {
  const service = document.getElementById('service').value;
  const reviewText = document.getElementById('reviewText').value.trim();

  if (!service || selectedRating === 0 || !reviewText) {
    alert('Please select a service, rating, and write a review.');
    return;
  }

  const reviewList = document.getElementById('reviewList');
  const reviewItem = document.createElement('div');
  reviewItem.className = 'review-item';

  reviewItem.innerHTML = `
    <h4>${service}</h4>
    <div class="stars">${'★'.repeat(selectedRating)}${'☆'.repeat(5 - selectedRating)}</div>
    <p>${reviewText}</p>
  `;

  reviewList.prepend(reviewItem);

  // Reset form
  document.getElementById('service').value = '';
  document.getElementById('reviewText').value = '';
  selectedRating = 0;
  updateStars();
}
