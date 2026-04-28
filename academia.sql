create database academiaDefault;

use academiaDefault;

create table plano(
	codigo int not null auto_increment primary key,
    nome varchar(100) not null,
    descricao varchar(200) not null,
    valor decimal(7,2) not null
)engine = InnoDB;

INSERT INTO plano (nome, descricao, valor) VALUES 
('Mensal', 'Plano básico mensal', 99.90),
('Trimestral', 'Plano trimestral com desconto', 259.90),
('Semestral', 'Plano semestral com mais desconto', 499.90),
('Anual', 'Plano anual completo', 899.90);

create table aluno(
	codigo int not null auto_increment primary key,
    nome varchar(150) not null,
    dtNascimento date not null,
    peso decimal(5,2) not null,
    altura decimal(4,2) not null,
    objetivo varchar(100) not null,
    email varchar(150) not null,
    senha varchar(255) not null,
    codigoPlano int not null,
    UNIQUE(email)
)engine = InnoDB;

create table instrutor(
	codigo int not null auto_increment primary key,
    nome varchar(150) not null,
    especialidade varchar(100) not null
)engine = InnoDB;

INSERT INTO instrutor (nome, especialidade) VALUES 
('Carlos Silva', 'Musculação'),
('Ana Oliveira', 'Funcional'),
('Roberto Santos', 'Crossfit'),
('Fernanda Costa', 'Pilates'),
('Marcos Lima', 'Natação');



create table treino(
	codigo int not null auto_increment primary key,
    objetivo varchar(100) not null,
    frequencia varchar(50) not null,
    duracao varchar(50) not null,
    codigoAluno int not null,
    codigoInstrutor int not null
)engine = InnoDB;



create table exercicio(
	codigo int not null auto_increment primary key,
    nome varchar(100) not null,
    descricao varchar(200) not null
)engine = InnoDB;

create table treinoExercicio(
	codigo int not null auto_increment primary key,
    codigoTreino int not null,
    codigoExercicio int not null,
    series int not null,
    repeticoes int not null
)engine = InnoDB;

create table avaliacao(
	codigo int not null auto_increment primary key,
    dataAvaliacao date not null,
    peso decimal(5,2) not null,
    gordura decimal(5,2) not null,
    observacoes varchar(200),
    codigoAluno int not null,
    codigoInstrutor int not null
)engine = InnoDB;

create table modalidade(
	codigo int not null auto_increment primary key,
    nome varchar(100) not null,
    descricao varchar(200) not null
)engine = InnoDB;

create table aula(
	codigo int not null auto_increment primary key,
    horario datetime not null,
    capacidade int not null,
    codigoModalidade int not null,
    codigoInstrutor int not null
)engine = InnoDB;

create table aulaAluno(
	codigo int not null auto_increment primary key,
    codigoAula int not null,
    codigoAluno int not null
)engine = InnoDB;



alter table aluno add constraint alunoPlano
foreign key (codigoPlano) references plano(codigo);

alter table treino add constraint treinoAluno
foreign key (codigoAluno) references aluno(codigo);

alter table treino add constraint treinoInstrutor
foreign key (codigoInstrutor) references instrutor(codigo);

alter table treinoExercicio add constraint treinoExercicioTreino
foreign key (codigoTreino) references treino(codigo);

alter table treinoExercicio add constraint treinoExercicioExercicio
foreign key (codigoExercicio) references exercicio(codigo);

alter table avaliacao add constraint avaliacaoAluno
foreign key (codigoAluno) references aluno(codigo);

alter table avaliacao add constraint avaliacaoInstrutor
foreign key (codigoInstrutor) references instrutor(codigo);

alter table aula add constraint aulaModalidadeNovo
foreign key (codigoModalidade) references modalidade(codigo);

alter table aula add constraint aulaInstrutor
foreign key (codigoInstrutor) references instrutor(codigo);

alter table aulaAluno add constraint aulaAlunoAula
foreign key (codigoAula) references aula(codigo);

alter table aulaAluno add constraint aulaAlunoAluno
foreign key (codigoAluno) references aluno(codigo);