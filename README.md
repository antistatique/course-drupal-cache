# Drupal -  Rendering & Caching magic revealed

## 👋 hello

Welcome to the companion repository for the course "Drupal - Rendering & Caching Magic Revealed"! This course is designed to challenge both beginners and experienced learners of Drupal, enabling you to unlock the full potential of caching capabilities in Drupal.

🍿 Slides: https://www.canva.com/design/DAFZ-aYG3EA/FXDqEg1yiETtHOkHyVIdkQ/view?utm_content=DAFZ-aYG3EA&utm_campaign=designshare&utm_medium=link&utm_source=publishsharelink

📒 Notion: https://jealous-star-d06.notion.site/Drupal-Rendering-Caching-magic-revealed-1ba404cc1139466fbce6545686494294

## 🛠 Requirements
- Docker
- Drupal

## 👾 Challenges

- [Challenge #1 - Caches Anatomy](./challenges/challenge_01.md)
- [Challenge #2 - Invalidation cache-tags](./challenges/challenge_02.md)

## 🧨 Getting Started

1. Clone this repository as 2 separated project. One Will be the challenges and the other one will be the Drupal project.

```
git clone --branch main https://github.com/antistatique/course-drupal-cache.git course-drupal-cache-challenges
git clone --branch drupal https://github.com/antistatique/course-drupal-cache.git course-drupal-cache-drupal
```

2. Bootstrap the Drupal Sandbox by following the [README.md](https://github.com/antistatique/course-drupal-cache/blob/drupal/README.md).
3. Mount any of the challenges as Docker volume in the `web/modules/custom` folder of the Drupal Sandbox container.

```yaml
services:
  dev:
    hostname: dev
    # ...

    volumes:
    # Challenges
      - ../course-drupal-cache-challenges/challenges/challenge-01/module/challenge_01:/var/www/web/modules/custom/challenge_01
```
