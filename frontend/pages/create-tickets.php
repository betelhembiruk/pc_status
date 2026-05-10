<?php include "../includes/header.php"; ?>
<?php include "../includes/sidebar.php"; ?>
<?php include "../includes/topbar.php"; ?>

<?php
$branches = [
    "41 Eyesus Branch",
    "Abiy Branch",
    "Addis Ababa Branch",
    "Afincho Ber Branch",
    "Amist Killo Branch",
    "Arada Ghiorgis Branch",
    "Arat Kilo Branch",
    "Bela Branch",
    "Bela Mazoria Branch",
    "Birhanina Selam Branch",
    "Churchill Godana Branch",
    "Dejach Wube Branch",
    "Diaspora Branch",
    "Etege Menen Branch",
    "Ferensay Legasion Branch",
    "Filwuha Branch",
    "Finfine Branch",
    "Gedam Sefer Branch",
    "Genet Tsigie Branch",
    "Gurara Kidane Mihret Branch",
    "Jan Meda Branch",
    "Kebena Branch",
    "Kidiste Mariam Branch",
    "Lagahar Branch",
    "Mahteme Ghandi Branch",
    "Mehal Ketema",
    "Menbere Patriarch Branch",
    "Meskel Square Branch",
    "Minilik Hospital Branch",
    "Piassa Branch",
    "Semen Mazegaja Branch",
    "Tayitu Bitul Branch",
    "Theodros Square Branch",
    "Tikur Anbessa Branch",
    "Tilahun Abay Branch",
    "Yared Branch"
];
?>

<div class="content" style="margin-left:260px; padding:30px; background:#f3f4f6; min-height:100vh;">

    <div style="display:flex; justify-content:center;">
        <div style="
            width:100%;
            max-width:520px;
            background:white;
            padding:25px;
            border-radius:12px;
            box-shadow:0 10px 25px rgba(0,0,0,0.1);
        ">

            <h2 style="
                text-align:center;
                margin-bottom:20px;
                color:#95298e;
            ">
                Create New Ticket
            </h2>

            <form id="ticketForm" style="
                display:flex;
                flex-direction:column;
                gap:12px;
            ">

                <!-- Serial Number -->
                <input
                    type="text"
                    name="serialNumber"
                    placeholder="Serial Number"
                    required
                    style="padding:12px; border:1px solid #ddd; border-radius:8px;"
                >

                <!-- Tag Number -->
                <input
                    type="text"
                    name="tagNumber"
                    placeholder="Tag Number"
                    style="padding:12px; border:1px solid #ddd; border-radius:8px;"
                >

                <!-- PC Model -->
                <input
                    type="text"
                    name="pcModel"
                    placeholder="Model"
                    style="padding:12px; border:1px solid #ddd; border-radius:8px;"
                >

                <!-- Hardware Type -->
                <select
                    name="hardwareType"
                    style="padding:12px; border:1px solid #ddd; border-radius:8px;"
                >
                    <option value="PC">PC</option>
                    <option value="Laptop">Laptop</option>
                    <option value="Printer">Printer</option>
                    <option value="Scanner">Scanner</option>
                    <option value="Other">Other</option>
                </select>

                <!-- Branch -->
                <select
                    name="branch"
                    required
                    style="padding:12px; border:1px solid #ddd; border-radius:8px;"
                >
                    <option value="">Select Branch</option>
                    <?php foreach ($branches as $branch): ?>
                        <option value="<?= htmlspecialchars($branch) ?>">
                            <?= htmlspecialchars($branch) ?>
                        </option>
                    <?php endforeach; ?>
                </select>

                <!-- Problem -->
                <input
                    type="text"
                    name="problem"
                    placeholder="Problem Description"
                    required
                    style="padding:12px; border:1px solid #ddd; border-radius:8px;"
                >

                <!-- Phone -->
                <input
                    type="text"
                    name="phone"
                    placeholder="Phone"
                    style="padding:12px; border:1px solid #ddd; border-radius:8px;"
                >

                <!-- Brought By -->
                <select
                    name="broughtBy"
                    style="padding:12px; border:1px solid #ddd; border-radius:8px;"
                >
                    <option value="">Brought By</option>
                    <option value="IT Department">IT Department</option>
                    <option value="Branch Staff">Branch Staff</option>
                </select>

                <!-- Submit -->
                <button
                    type="submit"
                    id="submitBtn"
                    style="
                        padding:12px;
                        background:#95298e;
                        color:white;
                        border:none;
                        border-radius:8px;
                        cursor:pointer;
                        font-weight:bold;
                        margin-top:10px;
                    "
                >
                    Create Ticket
                </button>
            </form>

            <!-- Message -->
            <p id="msg" style="
                text-align:center;
                margin-top:12px;
                font-weight:600;
            "></p>

        </div>
    </div>
</div>

<script>
document.getElementById("ticketForm").addEventListener("submit", async function(e) {
    e.preventDefault();

    const btn = document.getElementById("submitBtn");
    const msg = document.getElementById("msg");

    btn.disabled = true;
    btn.textContent = "Creating...";
    msg.style.color = "black";
    msg.textContent = "Submitting...";

    const formData = new FormData(this);
    const data = Object.fromEntries(formData.entries());

    try {
        const response = await fetch("/projects/PC_STATUS/backend/api/tickets/create.php", {
            method: "POST",
            headers: {
                "Content-Type": "application/json"
            },
            body: JSON.stringify(data)
        });

        // Read raw response first
        const raw = await response.text();
        console.log("RAW RESPONSE:", raw);

        let result;
        try {
            result = JSON.parse(raw);
        } catch (err) {
            msg.style.color = "red";
            msg.textContent = "Invalid JSON from server. Check browser console.";
            btn.disabled = false;
            btn.textContent = "Create Ticket";
            return;
        }

        if (result.success) {
            msg.style.color = "green";
            msg.textContent = result.message || "Ticket created successfully!";
            this.reset();
        } else {
            msg.style.color = "red";
            msg.textContent = result.message || "Failed to create ticket.";
        }

    } catch (error) {
        console.error("FETCH ERROR:", error);
        msg.style.color = "red";
        msg.textContent = "Fetch failed. Check browser console.";
    }

    btn.disabled = false;
    btn.textContent = "Create Ticket";
});
</script>

<?php include "../includes/footer.php"; ?>