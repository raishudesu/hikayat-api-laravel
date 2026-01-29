# 🥾 Hikayat System Design

## 1. Executive Summary

**Hikayat** is a niche social networking and booking platform designed specifically for the hiking community. It functions primarily as a "LinkedIn for Hikers," where users can build a professional-style hiking profile, share trail adventures, and facilitate professional connections through a verified guide-booking ecosystem.

---

## 2. Core Modules

### 2.1 User & Identity Management

- **Unified Profiles**: Standard users and Tour Guides share a base profile, but Guides have extended fields (experience, certifications, verification status).
- **Social Graph**: Users can follow and be followed by others, creating an interest-based feed.
- **Trust & Safety**:
    - **Email Verification**: Mandatory verification upon registration to ensure account authenticity.
    - **Password Recovery**: Secure token-based password reset mechanism.
- **Social Integration**: Ability to link Instagram, Strava, or Facebook for external trust signals.
- **Guide Verification**: A manual verification flow where users pay a subscription to receive a "Verified Guide" badge.

### 2.2 Social Engine (The Feed)

- **Content Types**: Posts can include text, high-res photos, and optional GPS coordinates.
- **Engagement**: Native support for likes, nested comments, and reposts (LinkedIn style).
- **Safety**: Flagging system for inappropriate content or trail misinformation.

### 2.3 Guide Booking & Subscription

- **Guide Marketplace**: A searchable index of verified guides.
- **Booking Flow**: A formal request-response system between hikers and guides.
- **Manual Subscription**: Admins toggle "Verified" status based on off-platform payments.

---

## 3. Database Architecture

### `users`

| Column              | Type    | Description                               |
| :------------------ | :------ | :---------------------------------------- |
| `id`                | UUID    | Primary Key                               |
| `first_name`        | string  |                                           |
| `last_name`         | string  |                                           |
| `email`             | string  | Unique                                    |
| `bio`               | text    | Professional summary                      |
| `social_links`      | json    | `{ "instagram": "...", "strava": "..." }` |
| `is_verified_guide` | boolean | Defaults to false                         |
| `avatar_path`       | string  |                                           |

### `posts`

| Column       | Type      | Description             |
| :----------- | :-------- | :---------------------- |
| `id`         | UUID      | Primary Key             |
| `user_id`    | foreignId | Author of the post      |
| `title`      | string    | Title of the post       |
| `content`    | text      | Markdown supported      |
| `latitude`   | decimal   | Optional (9,6)          |
| `longitude`  | decimal   | Optional (9,6)          |
| `parent_id`  | foreignId | For reposts (nullable)  |
| `deleted_at` | timestamp | Soft deletes for safety |

### `follows`

| Column         | Type      | Description                        |
| :------------- | :-------- | :--------------------------------- |
| `follower_id`  | foreignId | User who is following              |
| `following_id` | foreignId | User being followed                |
| `created_at`   | timestamp | Used for "Recently followed" lists |

### `interactions` (Likes/Reposts)

| Column            | Type      | Description      |
| :---------------- | :-------- | :--------------- |
| `id`              | BIGINT    | Primary Key      |
| `user_id`         | foreignId |                  |
| `interactable_id` | foreignId | Post ID          |
| `type`            | enum      | `like`, `repost` |

### `comments`

| Column      | Type      | Description          |
| :---------- | :-------- | :------------------- |
| `id`        | UUID      |                      |
| `user_id`   | foreignId |                      |
| `post_id`   | foreignId |                      |
| `parent_id` | foreignId | For nested threading |
| `content`   | text      |                      |

### `bookings`

| Column      | Type      | Description                                    |
| :---------- | :-------- | :--------------------------------------------- |
| `id`        | UUID      |                                                |
| `hiker_id`  | foreignId | User booking the service                       |
| `guide_id`  | foreignId | User providing the service                     |
| `status`    | enum      | `pending`, `accepted`, `declined`, `completed` |
| `hike_date` | date      |                                                |
| `notes`     | text      | Instructions or requirements                   |

### `subscriptions` (Admin Internal)

| Column       | Type      | Description                         |
| :----------- | :-------- | :---------------------------------- |
| `id`         | BIGINT    |                                     |
| `user_id`    | foreignId |                                     |
| `status`     | enum      | `active`, `expired`, `grace_period` |
| `starts_at`  | timestamp |                                     |
| `expires_at` | timestamp |                                     |
| `admin_ref`  | string    | Manual tracking (e.g., Receipt #)   |

### `password_reset_tokens` (Laravel Native Support)

| Column       | Type      | Description                        |
| :----------- | :-------- | :--------------------------------- |
| `email`      | string    | Primary Key / Foreign Key          |
| `token`      | string    | Secure hashed reset token          |
| `created_at` | timestamp | Used for token expiration checking |

### `reports` (Flags)

| Column          | Type      | Description                                     |
| :-------------- | :-------- | :---------------------------------------------- |
| `id`            | BIGINT    |                                                 |
| `reporter_id`   | foreignId | User who flagged                                |
| `reportable_id` | foreignId | Polymorphic (Post/Comment/Profile)              |
| `type`          | string    | e.g., `spam`, `inappropriate`, `misinformation` |
| `status`        | enum      | `pending`, `reviewed`, `dismissed`              |

---

## 4. Functional Specifications

### User Flow: Guide Verification

1. User requests "Guide Status" via Profile settings.
2. Platform provides payment instructions (Transfer/Cash).
3. Admin receives payment and uses the **Admin Console** to create a `subscription` record.
4. System sets `is_verified_guide = true` on the `User` model automatically based on an active subscription.

### Post Creation with Geospatial Data

- When a user creates a post, they can optionally attach GPS coordinates.
- **Best Practice**: The API should support GeoJSON format in the future, but for MVP, Simple Decimal (lat/long) is preferred for ease of use with mapping libraries like Leaflet or Mapbox.

---

## 5. Transactional Emails & Infrastructure

### 5.1 Communication Strategy

We will use Laravel's built-in **Notifications** and **Mail** abstractions to handle system emails.

- **Email Verification**: Sent via `MustVerifyEmail` trait immediately after registration.
- **Password Reset**: Standard Laravel `CanResetPassword` flow.
- **Booking Alerts**: Notifications sent to guides when a new booking is requested.

### 5.2 Recommended Services

1. **Mailtrap (Testing)**: For local/staging development to capture outbound emails without sending them to real addresses.
2. **Resend (Production)**: Modern and extremely easy to set up for a startup platform. Highly developer-friendly.
3. **Amazon SES (Scale)**: The most cost-effective solution once the user base exceeds 10k+ users.

---

## 6. Security & Best Practices

1. **Authorization (Laravel Policies)**:
    - Only the author can edit/delete posts.
    - Only Admins can create/edit subscription records.
    - Guides can only accept bookings where they are the `guide_id`.

2. **Data Integrity**:
    - Use **UUIDs** for public-facing resources (Posts, Users, Bookings) to prevent ID scraping.
    - **Soft Deletes**: Posts and Comments should use soft deletes to allow admins to review flagged content even after "deletion."

3. **Performance**:
    - Eager load `user` and `likes_count` when fetching the feed to avoid N+1 queries.
    - Index `is_verified_guide` and `post_id` columns.

---

## 6. Future Expansion

- **Messaging System**: Real-time chat between hiker and guide once a booking is "Accepted."
- **Payment Gateway Integration**: Automating the subscription flow via Stripe or PayPal.
- **Trail Database**: Integration with a "Trails" table so posts can be tagged to specific known mountains.
