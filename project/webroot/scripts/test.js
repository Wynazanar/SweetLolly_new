async function login() {
    const uuid = "f71c4a6f-d6ef-385a-994e-7c8aa0b18082";
    const baseUrl = "http://10.196.46.253:4567/v1/data/query";
    const headers = {
        "Content-Type": "application/json",
        "key": "mykey123"
    };

    const fetchPlayerData = async (query) => {
        const response = await fetch(baseUrl, {
            method: "POST",
            headers,
            body: JSON.stringify({
                query,
                player: { uuid }
            })
        });
        return response.json();
    };

    const [profile, points] = await Promise.all([
        fetchPlayerData("nlogin_profile"),
        fetchPlayerData("playerpoints_points")
    ]);

    console.log("Профиль:", profile.rows[0]);
    console.log("Поинты:", points.rows[0]?.points ?? 0);
}

// Тест
// login();