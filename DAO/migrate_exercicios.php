<?php
    /**
     * Script de migração - popula a tabela exercicio com exercícios de academia.
     * Seguro para rodar múltiplas vezes (verifica se já existem registros).
     */
    require_once __DIR__ . '/Conexao.php';
    use Projeto\DAO\Conexao;

    $conexao = new Conexao();
    $conn    = $conexao->conectar();

    if(!$conn){
        die("Erro ao conectar no banco de dados.");
    }

    echo "<h3>Migração - Exercícios de Academia</h3><hr>";

    // Verificar se já existem exercícios
    $result = mysqli_query($conn, "SELECT COUNT(*) as total FROM exercicio");
    $row    = mysqli_fetch_assoc($result);

    if($row['total'] > 0){
        echo "ℹ️ Já existem {$row['total']} exercícios cadastrados.<br>";
        echo "<strong style='color:lime;'>Nenhuma ação necessária.</strong>";
        mysqli_close($conn);
        exit;
    }

    // Lista de exercícios organizados por grupo muscular
    $exercicios = [
        // === PEITO ===
        ['Supino Reto com Barra',        'Exercício composto para peitoral maior, trabalha também tríceps e deltóide anterior.'],
        ['Supino Inclinado com Halteres', 'Foco na porção clavicular (superior) do peitoral, com halteres para maior amplitude.'],
        ['Supino Declinado',              'Enfatiza a porção inferior do peitoral, usando barra ou halteres.'],
        ['Crucifixo Reto',                'Exercício de isolamento para o peitoral, realizado com halteres no banco reto.'],
        ['Crossover na Polia',            'Exercício na polia para trabalhar o peitoral com tensão constante.'],
        ['Flexão de Braço',               'Exercício com peso corporal que trabalha peitoral, tríceps e core.'],

        // === COSTAS ===
        ['Puxada Frontal',                'Exercício no pulley para trabalhar latíssimo do dorso e bíceps.'],
        ['Remada Curvada com Barra',      'Exercício composto para espessura das costas, trabalha rombóides e trapézio.'],
        ['Remada Unilateral com Halter',  'Trabalha as costas de forma unilateral, corrigindo desequilíbrios musculares.'],
        ['Puxada Supinada',               'Variação da puxada com pegada supinada, maior ativação do bíceps.'],
        ['Remada Cavalinho (T-Bar)',       'Exercício para espessura das costas com barra em T.'],
        ['Pullover no Banco',             'Exercício que trabalha latíssimo do dorso e serrátil anterior.'],

        // === OMBROS ===
        ['Desenvolvimento com Halteres',  'Exercício composto para deltóides, realizado sentado ou em pé.'],
        ['Elevação Lateral',              'Isolamento do deltóide lateral para largura dos ombros.'],
        ['Elevação Frontal',              'Isolamento do deltóide anterior com halteres ou barra.'],
        ['Crucifixo Inverso',             'Trabalha deltóide posterior e rombóides, importante para postura.'],
        ['Encolhimento com Halteres',     'Exercício para trapézio superior, usando halteres.'],

        // === BÍCEPS ===
        ['Rosca Direta com Barra',        'Exercício básico para bíceps braquial com barra reta ou EZ.'],
        ['Rosca Alternada com Halteres',  'Trabalha o bíceps de forma alternada, permitindo supinação completa.'],
        ['Rosca Martelo',                 'Trabalha bíceps e braquiorradial com pegada neutra.'],
        ['Rosca Concentrada',             'Exercício de isolamento máximo do bíceps, sentado no banco.'],
        ['Rosca Scott',                   'Exercício no banco Scott para isolar a porção curta do bíceps.'],

        // === TRÍCEPS ===
        ['Tríceps Pulley (Corda)',        'Exercício na polia alta com corda para trabalhar as três cabeças do tríceps.'],
        ['Tríceps Francês',               'Exercício com halter ou barra atrás da cabeça para tríceps.'],
        ['Tríceps Testa',                 'Exercício deitado no banco com barra, trabalha a cabeça longa do tríceps.'],
        ['Mergulho no Banco',             'Exercício com peso corporal focado no tríceps e peitoral inferior.'],

        // === PERNAS (Quadríceps) ===
        ['Agachamento Livre',             'Exercício composto rei para quadríceps, glúteos e core.'],
        ['Leg Press 45°',                 'Exercício na máquina para quadríceps e glúteos com carga pesada.'],
        ['Cadeira Extensora',             'Isolamento do quadríceps na máquina extensora.'],
        ['Agachamento Hack',              'Variação do agachamento na máquina hack para quadríceps.'],
        ['Avanço (Passada)',              'Exercício unilateral para quadríceps, glúteos e equilíbrio.'],
        ['Agachamento Búlgaro',           'Agachamento unilateral com pé elevado, excelente para glúteos.'],

        // === PERNAS (Posterior) ===
        ['Mesa Flexora',                  'Isolamento dos isquiotibiais na máquina flexora deitado.'],
        ['Cadeira Flexora',               'Isolamento dos isquiotibiais na máquina flexora sentado.'],
        ['Stiff (Levantamento Terra Romeno)', 'Exercício para isquiotibiais e glúteos com barra.'],
        ['Levantamento Terra',            'Exercício composto que trabalha posterior de coxa, glúteos, costas e core.'],

        // === PERNAS (Glúteos e Panturrilha) ===
        ['Elevação Pélvica (Hip Thrust)', 'Exercício principal para ativação e hipertrofia dos glúteos.'],
        ['Abdução de Quadril na Máquina', 'Trabalha glúteo médio e mínimo na máquina abdutora.'],
        ['Panturrilha em Pé',             'Exercício para gastrocnêmio (panturrilha) na máquina em pé.'],
        ['Panturrilha Sentado',           'Exercício para sóleo (panturrilha profunda) na máquina sentado.'],

        // === ABDÔMEN ===
        ['Abdominal Crunch',              'Exercício básico para reto abdominal, deitado no chão.'],
        ['Prancha Isométrica',            'Exercício isométrico para fortalecimento do core completo.'],
        ['Abdominal Infra na Barra',      'Elevação de pernas na barra fixa para abdômen inferior.'],
        ['Abdominal Oblíquo',             'Exercício rotacional para oblíquos internos e externos.'],
        ['Roda Abdominal (Ab Wheel)',      'Exercício avançado para core com roda abdominal.'],

        // === CARDIO ===
        ['Esteira (Corrida)',             'Exercício cardiovascular na esteira para resistência e queima calórica.'],
        ['Bicicleta Ergométrica',         'Exercício cardiovascular de baixo impacto na bicicleta.'],
        ['Elíptico (Transport)',          'Exercício cardiovascular que simula caminhada/corrida sem impacto.'],
        ['Pular Corda',                   'Exercício cardiovascular de alta intensidade e coordenação.'],
    ];

    $sucesso = 0;
    $erros   = 0;

    foreach($exercicios as $ex){
        $nome      = mysqli_real_escape_string($conn, $ex[0]);
        $descricao = mysqli_real_escape_string($conn, $ex[1]);
        $sql       = "INSERT INTO exercicio (nome, descricao) VALUES ('$nome', '$descricao')";

        if(mysqli_query($conn, $sql)){
            $sucesso++;
        }else{
            echo "❌ Erro ao inserir '$nome': " . mysqli_error($conn) . "<br>";
            $erros++;
        }
    }//fim foreach

    echo "<br>✅ <strong>$sucesso</strong> exercícios inseridos com sucesso!<br>";
    if($erros > 0){
        echo "⚠️ <strong>$erros</strong> erros encontrados.<br>";
    }

    echo "<hr><strong style='color:lime;'>✅ Banco de dados atualizado!</strong><br><br>";
    echo "<a href='../index.php'>🏠 Ir para o Painel</a>";

    mysqli_close($conn);
?>
