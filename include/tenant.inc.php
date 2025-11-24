<?php

function tenant_id(): int
{
    return (int)($_SESSION['tenant_id'] ?? 1);
}

function is_global_admin(): bool
{
    return !empty($_SESSION['is_global_admin']);
}

function is_tenant_admin() {
    return !empty($_SESSION['is_tenant_admin']);
}

function sql_has_tenant_column(string $sql): bool
{
    // Nur filtern, wenn Tabellen enthalten sind, die tenant_id haben
    $sql = strtolower($sql);
    return (str_contains($sql, 'tenant_id'));
}

function add_tenant_filter(string $sql): string
{
    // GLOBAL ADMIN → keine Filterung
    if (is_global_admin()) {
        return $sql;
    }

    // Hat die Query keine tenant_id → nicht filtern
    if (!sql_has_tenant_column($sql)) {
        return $sql;
    }

    $lower = strtolower($sql);

    // WHERE bereits vorhanden?
    if (str_contains($lower, ' where ')) {
        return $sql . " AND tenant_id = " . tenant_id();
    }

    // Kein WHERE → hinzufügen
    return $sql . " WHERE tenant_id = " . tenant_id();
}
