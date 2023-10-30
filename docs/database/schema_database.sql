create table failed_jobs
(
    id         bigint unsigned auto_increment primary key,
    uuid       varchar(255)                        not null,
    connection text                                not null,
    queue      text                                not null,
    payload    longtext                            not null,
    exception  longtext                            not null,
    failed_at  timestamp default CURRENT_TIMESTAMP not null,
    constraint failed_jobs_uuid_unique unique (uuid)
) collate = utf8mb4_unicode_ci;
create table migrations
(
    id        int unsigned auto_increment primary key,
    migration varchar(255) not null,
    batch     int          not null
) collate = utf8mb4_unicode_ci;
create table password_reset_tokens
(
    email      varchar(255) not null primary key,
    token      varchar(255) not null,
    created_at timestamp    null
) collate = utf8mb4_unicode_ci;
create table payment_methods
(
    id          bigint unsigned auto_increment primary key,
    name        varchar(255) not null,
    description varchar(255) not null,
    created_at  timestamp    null,
    updated_at  timestamp    null
) collate = utf8mb4_unicode_ci;
create table payment_statuses
(
    id          bigint unsigned auto_increment primary key,
    name        varchar(255) not null,
    description varchar(255) not null,
    created_at  timestamp    null,
    updated_at  timestamp    null
) collate = utf8mb4_unicode_ci;
create table payments
(
    id                bigint unsigned auto_increment primary key,
    payment_method_id bigint unsigned not null,
    status_id         bigint unsigned not null,
    amount            decimal(10, 2)  not null,
    payment_date      timestamp       null,
    created_at        timestamp       null,
    updated_at        timestamp       null,
    constraint payments_payment_method_id_foreign foreign key (payment_method_id) references payment_methods (id),
    constraint payments_status_id_foreign foreign key (status_id) references payment_statuses (id)
) collate = utf8mb4_unicode_ci;
create table permissions
(
    id         bigint unsigned auto_increment primary key,
    name       varchar(255) not null,
    guard_name varchar(255) not null,
    created_at timestamp    null,
    updated_at timestamp    null,
    constraint permissions_name_guard_name_unique unique (name, guard_name)
) collate = utf8mb4_unicode_ci;
create table model_has_permissions
(
    permission_id bigint unsigned not null,
    model_type    varchar(255)    not null,
    model_id      bigint unsigned not null,
    team_id       bigint unsigned not null,
    primary key (team_id, permission_id, model_id, model_type),
    constraint model_has_permissions_permission_id_foreign foreign key (permission_id) references permissions (id) on delete cascade
) collate = utf8mb4_unicode_ci;
create index model_has_permissions_model_id_model_type_index on model_has_permissions (model_id, model_type);
create index model_has_permissions_team_foreign_key_index on model_has_permissions (team_id);
create table personal_access_tokens
(
    id             bigint unsigned auto_increment primary key,
    tokenable_type varchar(255)    not null,
    tokenable_id   bigint unsigned not null,
    name           varchar(255)    not null,
    token          varchar(64)     not null,
    abilities      text            null,
    last_used_at   timestamp       null,
    expires_at     timestamp       null,
    created_at     timestamp       null,
    updated_at     timestamp       null,
    constraint personal_access_tokens_token_unique unique (token)
) collate = utf8mb4_unicode_ci;
create index personal_access_tokens_tokenable_type_tokenable_id_index on personal_access_tokens (tokenable_type, tokenable_id);
create table roles
(
    id         bigint unsigned auto_increment primary key,
    team_id    bigint unsigned null,
    name       varchar(255)    not null,
    guard_name varchar(255)    not null,
    created_at timestamp       null,
    updated_at timestamp       null,
    constraint roles_team_id_name_guard_name_unique unique (team_id, name, guard_name)
) collate = utf8mb4_unicode_ci;
create table model_has_roles
(
    role_id    bigint unsigned not null,
    model_type varchar(255)    not null,
    model_id   bigint unsigned not null,
    team_id    bigint unsigned not null,
    primary key (team_id, role_id, model_id, model_type),
    constraint model_has_roles_role_id_foreign foreign key (role_id) references roles (id) on delete cascade
) collate = utf8mb4_unicode_ci;
create index model_has_roles_model_id_model_type_index on model_has_roles (model_id, model_type);
create index model_has_roles_team_foreign_key_index on model_has_roles (team_id);
create table role_has_permissions
(
    permission_id bigint unsigned not null,
    role_id       bigint unsigned not null,
    primary key (permission_id, role_id),
    constraint role_has_permissions_permission_id_foreign foreign key (permission_id) references permissions (id) on delete cascade,
    constraint role_has_permissions_role_id_foreign foreign key (role_id) references roles (id) on delete cascade
) collate = utf8mb4_unicode_ci;
create index roles_team_foreign_key_index on roles (team_id);
create table sectors
(
    id         bigint unsigned auto_increment primary key,
    name       varchar(255) not null,
    capacity   int          not null,
    created_at timestamp    null,
    updated_at timestamp    null
) collate = utf8mb4_unicode_ci;
create table ticket_statuses
(
    id          bigint unsigned auto_increment primary key,
    name        varchar(255) not null,
    description varchar(255) not null,
    created_at  timestamp    null,
    updated_at  timestamp    null
) collate = utf8mb4_unicode_ci;
create table users
(
    id                bigint unsigned auto_increment primary key,
    name              varchar(255) not null,
    phone             varchar(20)  null,
    cpf_cnpj          varchar(20)  null,
    email             varchar(255) not null,
    email_verified_at timestamp    null,
    password          varchar(255) not null,
    remember_token    varchar(100) null,
    created_at        timestamp    null,
    updated_at        timestamp    null,
    constraint users_cpf_cnpj_unique unique (cpf_cnpj),
    constraint users_email_unique unique (email)
) collate = utf8mb4_unicode_ci;
create table logs
(
    id          bigint unsigned auto_increment primary key,
    user_id     bigint unsigned null,
    event_type  varchar(255)    not null,
    description text            not null,
    created_at  timestamp       null,
    updated_at  timestamp       null,
    constraint logs_user_id_foreign foreign key (user_id) references users (id) on delete
        set null
) collate = utf8mb4_unicode_ci;
create table orders
(
    id           bigint unsigned auto_increment primary key,
    user_id      bigint unsigned not null,
    total_amount decimal(10, 2)  not null,
    status       varchar(255)    not null,
    payment_id   bigint unsigned not null,
    created_at   timestamp       null,
    updated_at   timestamp       null,
    constraint orders_payment_id_foreign foreign key (payment_id) references payments (id),
    constraint orders_user_id_foreign foreign key (user_id) references users (id)
) collate = utf8mb4_unicode_ci;
create table order_items
(
    id         bigint unsigned auto_increment primary key,
    order_id   bigint unsigned not null,
    subtotal   decimal(10, 2)  not null,
    created_at timestamp       null,
    updated_at timestamp       null,
    constraint order_items_order_id_foreign foreign key (order_id) references orders (id)
) collate = utf8mb4_unicode_ci;
create table teams
(
    id         bigint unsigned auto_increment primary key,
    user_id    bigint unsigned not null,
    name       varchar(255)    not null,
    created_at timestamp       null,
    updated_at timestamp       null,
    constraint teams_user_id_foreign foreign key (user_id) references users (id)
) collate = utf8mb4_unicode_ci;
create table events
(
    id         bigint unsigned auto_increment primary key,
    user_id    bigint unsigned not null,
    team_id    bigint unsigned not null,
    name       varchar(255)    not null,
    datetime   datetime        not null,
    location   varchar(255)    not null,
    banner     varchar(255)    not null,
    created_at timestamp       null,
    updated_at timestamp       null,
    constraint events_team_id_foreign foreign key (team_id) references teams (id),
    constraint events_user_id_foreign foreign key (user_id) references users (id) on delete cascade
) collate = utf8mb4_unicode_ci;
create table coupons
(
    id                  bigint unsigned auto_increment primary key,
    event_id            bigint unsigned not null,
    code                varchar(7)      not null,
    discount_percentage decimal(5, 2)   not null,
    max_usages          int             null,
    expiration_date     date            null,
    user_id             bigint unsigned not null,
    created_at          timestamp       null,
    updated_at          timestamp       null,
    constraint coupons_code_unique unique (code),
    constraint coupons_event_id_code_unique unique (event_id, code),
    constraint coupons_event_id_foreign foreign key (event_id) references events (id),
    constraint coupons_user_id_foreign foreign key (user_id) references users (id)
) collate = utf8mb4_unicode_ci;
create table coupon_usages
(
    id         bigint unsigned auto_increment primary key,
    coupon_id  bigint unsigned not null,
    order_id   bigint unsigned not null,
    created_at timestamp       null,
    updated_at timestamp       null,
    constraint coupon_usages_coupon_id_foreign foreign key (coupon_id) references coupons (id),
    constraint coupon_usages_order_id_foreign foreign key (order_id) references orders (id)
) collate = utf8mb4_unicode_ci;
create table lots
(
    id                bigint unsigned auto_increment primary key,
    event_id          bigint unsigned not null,
    name              varchar(255)    not null,
    available_tickets int             null,
    expiration_date   date            null,
    created_at        timestamp       null,
    updated_at        timestamp       null,
    constraint lots_event_id_foreign foreign key (event_id) references events (id) on delete cascade
) collate = utf8mb4_unicode_ci;
create table lot_sector
(
    id         bigint unsigned auto_increment primary key,
    lot_id     bigint unsigned not null,
    sector_id  bigint unsigned not null,
    created_at timestamp       null,
    updated_at timestamp       null,
    constraint lot_sector_lot_id_foreign foreign key (lot_id) references lots (id) on delete cascade,
    constraint lot_sector_sector_id_foreign foreign key (sector_id) references sectors (id) on delete cascade
) collate = utf8mb4_unicode_ci;
create table ticket_prices
(
    id         bigint unsigned auto_increment primary key,
    sector_id  bigint unsigned not null,
    lot_id     bigint unsigned not null,
    price      decimal(10, 2)  not null,
    created_at timestamp       null,
    updated_at timestamp       null,
    constraint ticket_prices_lot_id_foreign foreign key (lot_id) references lots (id) on delete cascade,
    constraint ticket_prices_sector_id_foreign foreign key (sector_id) references sectors (id) on delete cascade
) collate = utf8mb4_unicode_ci;
create table tickets
(
    id              bigint unsigned auto_increment primary key,
    user_id         bigint unsigned not null,
    status_id       bigint unsigned not null,
    ticket_price_id bigint unsigned not null,
    order_item_id   bigint unsigned not null,
    created_at      timestamp       null,
    updated_at      timestamp       null,
    constraint tickets_order_item_id_foreign foreign key (order_item_id) references order_items (id),
    constraint tickets_status_id_foreign foreign key (status_id) references ticket_statuses (id),
    constraint tickets_ticket_price_id_foreign foreign key (ticket_price_id) references ticket_prices (id),
    constraint tickets_user_id_foreign foreign key (user_id) references users (id)
) collate = utf8mb4_unicode_ci;
