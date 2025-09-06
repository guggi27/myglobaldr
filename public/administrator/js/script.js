const accessTokenKey = "MyGlobalDrAdminAccessToken"

const globalState = {
    state: {
        user: null
    },

    listeners: [],

    listen (callBack) {
        this.listeners.push(callBack)
    },

    setState (newState) {
        this.state = {
            ...this.state,
            ...newState
        }

        for (let a = 0; a < this.listeners.length; a++) {
            this.listeners[a](this.state, newState)
        }
    }
}

const months = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];

function removeFromFeatured(jobId, title) {
    const node = event.target;

    swal.fire({
        title: "Remove job '" + title + "' from featured?",
        text: "This will stop displaying at top in job listing.",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes, do it!"
    }).then(async function (result) {
        if (result.isConfirmed) {
            node.setAttribute("disabled", "disabled");

            const formData = new FormData();
            formData.append("id", jobId);

            try {
                const response = await axios.post(
                    baseUrl + "/admin/jobs/remove-from-featured",
                    formData,
                    {
                        headers: {
                            Authorization: "Bearer " + localStorage.getItem(accessTokenKey)
                        }
                    }
                );

                if (response.data.status == "success") {
                    swal.fire("Remove featured job", response.data.message, "success");
                } else {
                    swal.fire("Error", response.data.message, "error");
                }
            } catch (exp) {
                console.log(exp.message);
            } finally {
                node.removeAttribute("disabled");
            }
        }
    });
}

function markAsFeatured(jobId, title) {
    const node = event.target;

    swal.fire({
        title: "Mark job '" + title + "' as featured?",
        text: "This will start displaying at top in job listing.",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes, do it!"
    }).then(async function (result) {
        if (result.isConfirmed) {
            node.setAttribute("disabled", "disabled");

            const formData = new FormData();
            formData.append("id", jobId);

            try {
                const response = await axios.post(
                    baseUrl + "/admin/jobs/mark-as-featured",
                    formData,
                    {
                        headers: {
                            Authorization: "Bearer " + localStorage.getItem(accessTokenKey)
                        }
                    }
                );

                if (response.data.status == "success") {
                    swal.fire("Feature job", response.data.message, "success");
                } else {
                    swal.fire("Error", response.data.message, "error");
                }
            } catch (exp) {
                console.log(exp.message);
            } finally {
                node.removeAttribute("disabled");
            }
        }
    });
}

window.addEventListener("load", function () {
    const timeZone = Intl.DateTimeFormat().resolvedOptions().timeZone || "";
    const timezones = document.querySelectorAll(".timezone");
    for (let a = 0; a < timezones.length; a++) {
        const time = timezones[a].innerHTML;

        const newTime = new Intl.DateTimeFormat('en-GB', {
            timeZone: timeZone,
            // year: 'numeric',
            // month: '2-digit',
            // day: '2-digit',
            // hour: '2-digit',
            // minute: '2-digit',
            // second: '2-digit',
            // hour12: true,
            dateStyle: "full",
            timeStyle: "long"
        }).format(new Date(time + " UTC"));

        timezones[a].innerHTML = newTime;
        timezones[a].style.display = "block";
    }
});

function openBase64File(base64String, fileType) {
    // Decode base64 to binary data
    const byteCharacters = atob(base64String);
    const byteNumbers = new Array(byteCharacters.length);
    
    for (let i = 0; i < byteCharacters.length; i++) {
        byteNumbers[i] = byteCharacters.charCodeAt(i);
    }
    
    const byteArray = new Uint8Array(byteNumbers);
    const blob = new Blob([byteArray], { type: fileType });

    // Create a link pointing to the Blob
    const blobURL = URL.createObjectURL(blob);
    window.open(blobURL, '_blank');
}