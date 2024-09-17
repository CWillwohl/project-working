<div class="flex flex-row justify-center w-full">
    <div class="flex justify-center w-2/3 p-4">
        <div class="flex flex-col text-center sm:flex-row">
            <span class="text-6xl font-bold w-18">
                <span id="hours">00</span>
                <span class="hidden lg:inline">:</span>
                <span id="minutes">00</span>
            </span>
        </div>
    </div>
</div>

<script>
    function updateClock() {
        var now = new Date();
        var hours = now.getHours();
        var minutes = now.getMinutes();

        hours = (hours < 10) ? "0" + hours : hours;
        minutes = (minutes < 10) ? "0" + minutes : minutes;

        document.getElementById("hours").innerText = hours;
        document.getElementById("minutes").innerText = minutes;
    }

setInterval(updateClock, 1000);
</script>
