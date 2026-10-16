<?php
// Abre a página: <head>, glifos, moldura de pergaminho e menu.
// Antes de incluir, a página define $titulo e, se for interna, $ativa (nome do item do menu).
// A página de login define $publica = true para esconder o menu.
$publica = $publica ?? false;
$ativa = $ativa ?? '';
$menu = [
    'inicio'      => ['principal.php',   'Início'],
    'jogadores'   => ['jogadores.php',   'Jogadores'],
    'personagens' => ['personagens.php', 'Personagens'],
    'diario'      => ['diario.php',      'Diário de Sessões'],
];
$glifos_margem = ['g-runa1', 'g-lua', 'g-runa3', 'g-olho', 'g-runa2', 'g-estrela', 'g-runa1'];
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= htmlspecialchars($titulo) ?> | Grimório do Mestre</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cinzel+Decorative:wght@700&family=Cinzel:wght@500;700&family=IM+Fell+English:ital@0;1&display=swap" rel="stylesheet">
    <!-- Folha de estilo padrão do sistema (todas as telas usam o mesmo arquivo css/style.css) -->
    <link rel="stylesheet" href="css/style.css">
</head>
<body class="<?= $publica ? 'publica' : '' ?>">
<?php include __DIR__ . '/glifos.php'; ?>

<div class="palco">
  <div class="folha">
    <div class="marca-dagua" aria-hidden="true">
      <svg viewBox="0 0 100 100"><use href="#g-circulo"/></svg>
    </div>

    <div class="margem esq" aria-hidden="true">
      <?php foreach ($glifos_margem as $g): ?><svg viewBox="0 0 100 100"><use href="#<?= $g ?>"/></svg><?php endforeach; ?>
    </div>
    <div class="margem dir" aria-hidden="true">
      <?php foreach (array_reverse($glifos_margem) as $g): ?><svg viewBox="0 0 100 100"><use href="#<?= $g ?>"/></svg><?php endforeach; ?>
    </div>

    <header class="cabeca">
      <div class="divisor" aria-hidden="true"><svg viewBox="0 0 100 100"><use href="#g-lua"/></svg></div>
      <h1 class="marca">Grimório do Mestre</h1>
      <p class="lema">Diário de mesa de RPG</p>

      <?php if (!$publica): ?>
      <nav class="menu">
        <?php foreach ($menu as $chave => [$url, $rotulo]): ?>
          <a href="<?= $url ?>" class="<?= $ativa === $chave ? 'ativo' : '' ?>"><?= $rotulo ?></a>
        <?php endforeach; ?>
        <a href="logout.php" class="sair">Sair</a>
      </nav>
      <?php endif; ?>
    </header>

    <main class="conteudo">
