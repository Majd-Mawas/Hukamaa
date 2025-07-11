document.addEventListener("DOMContentLoaded", function () {
    function fetchWithCsrf(url, options = {}) {
        const csrfToken =
            document
                .querySelector('meta[name="csrf-token"]')
                ?.getAttribute("content") || "";

        const defaultHeaders = {
            "X-CSRF-TOKEN": csrfToken,
            "Content-Type": "application/json",
            Accept: "application/json",
        };

        return fetch(url, {
            ...options,
            headers: {
                ...defaultHeaders,
                ...options.headers,
            },
        });
    }

    // Handle modal opening for editing existing time slots
    document.querySelectorAll(".edit-availability").forEach((button) => {
        button.addEventListener("click", function () {
            const id = this.getAttribute("data-id");
            document.getElementById("availabilityId").value = id;
            document.getElementById("weekday").value = "";
            document.getElementById("modal-title").textContent =
                "Edit Time Slot";

            fetch(`/dashboard/doctor/availabilities/${id}`)
                .then((response) => response.json())
                .then((data) => {
                    document.getElementById("startTime").value =
                        data.start_time.substring(0, 5);
                    document.getElementById("endTime").value =
                        data.end_time.substring(0, 5);
                });
        });
    });

    // Handle modal opening for adding new time slots
    document.querySelectorAll(".add-availability").forEach((button) => {
        button.addEventListener("click", function () {
            const weekday = this.getAttribute("data-weekday");
            document.getElementById("availabilityId").value = "";
            document.getElementById("weekday").value = weekday;
            document.getElementById("modal-title").textContent =
                "Add Time Slot";

            // Set default values for new time slot
            document.getElementById("startTime").value = "09:00";
            document.getElementById("endTime").value = "17:00";
        });
    });

    // Handle delete button clicks
    document.querySelectorAll(".delete-availability").forEach((button) => {
        button.addEventListener("click", function () {
            if (confirm("Are you sure you want to delete this time slot?")) {
                const id = this.getAttribute("data-id");

                fetchWithCsrf(`/dashboard/doctor/availabilities/${id}`, {
                    method: "DELETE",
                })
                    .then((response) => response.json())
                    .then((data) => {
                        // Remove the time slot from the UI
                        const timeSlotItem = this.closest(".time-slot-item");
                        if (timeSlotItem) {
                            timeSlotItem.remove();

                            // Check if there are any remaining time slots
                            const container =
                                document.querySelector(".time-slots");
                            if (!container) {
                                console.error("Time slots container not found");
                                return;
                            }
                            if (
                                container &&
                                container.querySelectorAll(".time-slot-item")
                                    .length === 0
                            ) {
                                const noSlotsMessage =
                                    document.createElement("span");
                                noSlotsMessage.className = "text-neutral-500";
                                noSlotsMessage.textContent =
                                    "No time slots available";
                                container.appendChild(noSlotsMessage);
                            }
                        }
                    })
                    .catch((error) => console.error("Error:", error));
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
            const weekday = formData.get("weekday");

            let url, method;

            if (id) {
                // Editing existing time slot
                url = `/dashboard/doctor/availabilities/${id}`;
                method = "PUT";
            } else {
                // Adding new time slot
                url = "/dashboard/doctor/availabilities";
                method = "POST";
            }

            fetchWithCsrf(url, {
                method: method,
                body: JSON.stringify({
                    weekday: weekday || undefined,
                    start_time: formData.get("start_time"),
                    end_time: formData.get("end_time"),
                }),
            })
                .then((response) => response.json())
                .then((data) => {
                    // Close the modal using Flowbite
                    const modalElement =
                        document.getElementById("availability-modal");
                    // const modal = flowbite.Modal.getInstance(modalElement);
                    // modal.hide();

                    // Refresh the page to show updated time slots
                    window.location.reload();
                })
                .catch((error) => console.error("Error:", error));
        });
});

function validateHoursOnly(event) {
    const timeInputs = document.querySelectorAll('input[type="time"]');
    for (const input of timeInputs) {
        const [hour, minute] = input.value.split(":");
        if (minute !== "00") {
            alert(
                `Please select a full hour only in ${input.name.replace(
                    "_",
                    " "
                )}.`
            );
            input.focus();
            event.preventDefault();
            return false;
        }
    }
    return true;
}

// Optional: Automatically correct the value if user selects non-zero minutes
document.querySelectorAll('input[type="time"]').forEach((input) => {
    input.addEventListener("change", () => {
        const [hour, minute] = input.value.split(":");
        if (minute !== "00") {
            input.value = `${hour}:00`;
        }
    });
});
