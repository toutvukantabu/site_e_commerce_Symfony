# E-commerce Website Documentation

## Prerequisites

Ensure you have the following installed on your machine:

- **Docker** and **Docker Compose**. If there is a conflict with your version, you can modify it in the `version` field of the `docker-compose.yml` file.

  - Install Docker: [Docker Installation Guide](https://docs.docker.com/get-docker/)
  - Install Docker Compose: [Docker Compose Installation Guide](https://docs.docker.com/compose/install/)

- **Stripe API Keys** (if you plan to test Stripe payments). Add your API keys to the `.env` file:
  
  - Get your API keys: [Stripe Dashboard](https://dashboard.stripe.com/test/apikeys)

---

## Project Startup

After cloning the repository, open a terminal at the root of the project (`~/site_e_commerce_Symfony`) and follow these steps:

### Step 1: Start the Project
```sh
make start
```

### Step 2: Access the Symfony Container
To enter the Symfony container (`www_symfony_docker`), run:
```sh
make sh
```
To return to the basic terminal, type:
```sh
exit
```

---

## Project Access

- **Frontend Access:** [https://localhost:444](https://localhost:444)
- If you need assistance, check the `MAKEFILE` or run:
  ```sh
  make help
  ```
  to view the list of available commands.

---

## Administration Panel

- **Login:** `admin@gmail.com`
- **Password:** `password`

---

## MailDev Access (Email Testing)

- **URL:** [http://localhost:8082](http://localhost:8082)

---

## PostgreSQL Database Connection

- **Login:** `shop`
- **Password:** `shop`
- **Host:** `localhost`
- **Port:** `5433`

---

## Additional Notes

- Ensure that Docker and all necessary services are running before accessing the project.
- For any troubleshooting, refer to the `MAKEFILE` for useful commands.

Happy coding! 🚀

