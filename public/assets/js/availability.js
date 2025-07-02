document.addEventListener("DOMContentLoaded", function () {
    // Handle modal opening for both edit and add
    document.querySelectorAll(".edit-availability").forEach((button) => {
        button.addEventListener("click", function () {
            const id = this.getAttribute("data-id");
            document.getElementById("availabilityId").value = id;

            // If editing existing availability, populate form
            if (!isNaN(id)) {
                // Check if it's an ID (edit) rather than weekday value (add)
                fetch(`/dashboard/doctor/availability/${id}`)
                    .then((response) => response.json())
                    .then((data) => {
                        document.getElementById("startTime").value =
                            data.start_time;
                        document.getElementById("endTime").value =
                            data.end_time;
                    });
            } else {
                // For new availability, set default values
                document.getElementById("startTime").value = "09:00";
                document.getElementById("endTime").value = "17:00";
            }
        });
    });

    // Handle form submission
    document
        .getElementById("availabilityForm")
        .addEventListener("submit", function (e) {
            e.preventDefault();

            const formData = new FormData(this);
            const id = formData.get("id");
            const url = isNaN(id)
                ? "/dashboard/doctor/availability"
                : `/dashboard/doctor/availability/${id}`;
            const method = isNaN(id) ? "POST" : "PUT";

            fetch(url, {
                method: method,
                headers: {
                    "X-CSRF-TOKEN": formData.get("_token"),
                    "Content-Type": "application/json",
                    Accept: "application/json",
                },
                body: JSON.stringify({
                    weekday: isNaN(id) ? id : undefined,
                    start_time: formData.get("start_time"),
                    end_time: formData.get("end_time"),
                }),
            })
                .then((response) => response.json())
                .then((data) => {
                    const row = document.querySelector(
                        `tr[data-id="${data.id || id}"]`
                    );
                    if (row) {
                        row.querySelector(".start-time").textContent =
                            data.start_time;
                        row.querySelector(".end-time").textContent =
                            data.end_time;
                        row.querySelector(".available").textContent =
                            "Available";
                    }
                    // Close the modal using jQuery
                    $("#availability-modal").modal("hide");
                })
                .catch((error) => console.error("Error:", error));
        });
});
