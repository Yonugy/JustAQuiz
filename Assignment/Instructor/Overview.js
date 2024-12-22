let open = document.querySelector(".edit");
let close = document.getElementById("close-button");
let target = document.querySelector(".popup-edit");
let submit = document.querySelector(".pop-up-submit");
let feedbackInput = document.querySelector(".pop-up-input");

let resultId;

open.addEventListener("click", (event) => {
  target.style.display = "flex";
  resultId = event.target.closest("tr").getAttribute("data-result-id");
});

close.addEventListener("click", () => {
  target.style.display = "none";
});

submit.addEventListener("click", () => {
  let feedback = feedbackInput.value.trim(); // Get the feedback value

  if (feedback && resultId) {
    // Send the feedback and result_id to the backend (AJAX)
    let xhr = new XMLHttpRequest();
    xhr.open("POST", "Overview.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

    // Send the data to the backend
    xhr.send(`result_id=${resultId}&feedback=${encodeURIComponent(feedback)}`);

    xhr.onload = function () {
      if (xhr.status == 200) {
        alert("Feedback submitted successfully!");
        target.style.display = "none";
        location.reload();
      } else {
        alert("Failed to submit feedback.");
      }
    };
  }
});
