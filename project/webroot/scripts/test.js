async function getPlayers() {
    const response = await fetch(
        "http://10.196.46.253:4567/v1/players/",
        {
            headers: {
                key: "mykey123"
            }
        }
    );

    const data = await response.json();

    console.log(data);
}

getPlayers();

async function login(login, password) {
    const response = await fetch("http://10.196.46.253:4567/v1/auth/login", {  // без /
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "key": "mykey123"          // должен совпадать с key в config.yml ServerTap
        },
        body: JSON.stringify({
            login: login,
            password: password
        })
    });

    const data = await response.json();
    console.log(data);

    if (data.success) {
        console.log("Успешный вход:", data.username);
    } else {
        console.log("Ошибка:", data.message);
    }

    return data;
}

// Тест
// login("Wynazanar", "123123");