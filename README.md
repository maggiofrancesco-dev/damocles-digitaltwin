# DAMOCLES

## Table of Contents

1. [Description](#description)
2. [Installation](#installation)
    - [Installation with Docker](#installation-with-docker)
    - [Installation without Docker](#installation-without-docker)
3. [Login](#login)

## Description

The main purpose of the system is to support the design of education, training, and awareness interventions on phishing through the use of questionnaire campaigns and phishing email campaigns. It leverages Large Language Models (LLM) to generate phishing emails adapted to various human factors.

## Installation

Copy the `.env.example` file rename it to `.env`:

```bash
cp .env.example .env
```

It is important to replace the example data indicated as `CHANGEME` in the `.env` file.

Now you can choose the type of installation, with or without Docker.

### Installation with Docker

Requirements:

-   Docker

After cloning the repository and moving to the repository folder and copying the `.env.example` to the `.env` file and replacing the `CHANGEME` examples, run the following command to launch the containers using Docker Compose:

In the foreground:

```bash
docker compose up
```

or in the background:

```bash
docker compose up -d
```

The system is available on `localhost` or `http://127.0.0.1`.

## Login

Access to the platform is possible through two available profiles and roles:

-   "Admin" (email admin@damocles.com, password: secretAdmin)
-   "Evaluator" (email evaluator@damocles.com, password: secretEvaluator)

While all new users who register will have the role of "User".

Keep in mind that for testing purposes fake users will be created at the application startup, to remove them check UsersSeeder.php
