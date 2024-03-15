<?php

abstract class Model
{
    protected $db;
    protected $table;


    public function __construct($table)
    {
        $this->db = $this->getDB();
        $this->table = $table;
    }

    public function getDB()
    {
        try {
            $conn = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASS);
            return $conn;
        } catch (PDOException $e) {
            $conn = new PDO("mysql:host=" . DB_HOST, DB_USER, DB_PASS);

            $sql = "CREATE DATABASE IF NOT EXISTS `tpl_pedro` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;";
            $conn->exec($sql);

            $conn = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASS);

            $sql = '--
            -- Estructura de tabla para la tabla `directores`
            --
            
            CREATE TABLE `directores` (
              `id` int(11) NOT NULL,
              `nombre` varchar(50) NOT NULL,
              `edad` int(11) NOT NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
            
            --
            -- Volcado de datos para la tabla `directores`
            --
            
            INSERT INTO `directores` (`id`, `nombre`, `edad`) VALUES
            (1, "Chris Columbus", 65),
            (2, "Peter Jackson", 62),
            (3, "Steven Spielberg", 77);';

            $conn->exec($sql);

            $sql = '--
            -- Estructura de tabla para la tabla `peliculas`
            --
            
            CREATE TABLE `peliculas` (
              `id` int(11) NOT NULL,
              `nombre` varchar(50) NOT NULL,
              `director` int(11) NOT NULL,
              `duracion` int(11) NOT NULL,
              `fecha_estreno` date NOT NULL,
              `presupuesto` int(11) NOT NULL,
              `img` varchar(250) NOT NULL DEFAULT ""
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
            
            --
            -- Volcado de datos para la tabla `peliculas`
            --
            
            INSERT INTO `peliculas` (`id`, `nombre`, `director`, `duracion`, `fecha_estreno`, `presupuesto`, `img`) VALUES
            (1, "Harry Potter 1", 1, 120, "2001-03-20", 88000, "./src/imgs/65f25b5496e283.39443744.jpeg"),
            (2, "Señor de los anillos 1", 2, 120, "2001-03-20", 150000, "./src/imgs/65f25b40b155b0.41579135.jpg"),
            (3, "Harry Potter 2", 1, 120, "2001-03-21", 155000, "./src/imgs/65f25b63eb9100.36264857.jpg"),
            (4, "Señor de los anillos 2", 2, 120, "2007-03-25", 566040, "./src/imgs/65f25b7275b3d3.35103577.jpg"),
            (19, "Harry Potter 3", 1, 120, "2015-03-20", 1560, "./src/imgs/65f25b7a3d8971.93774929.jpg"),
            (20, "Señor de los anillos 3", 2, 120, "2015-03-20", 3210, "./src/imgs/65f25b81a653d7.39960418.jpg"),
            (21, "Harry Potter 4", 1, 120, "2020-02-05", 150040, "./src/imgs/65f25be0729af1.96620415.jpg"),
            (22, "Harry Potter 5", 1, 140, "1999-03-20", 5, "./src/imgs/65f2f929c1a8a1.83230881.jpg");
            ';

            $conn->exec($sql);

            $sql = '--
            -- Estructura de tabla para la tabla `usuarios`
            --
            
            CREATE TABLE `usuarios` (
              `id` int(11) NOT NULL,
              `username` varchar(80) NOT NULL,
              `password` varchar(100) NOT NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
            
            --
            -- Volcado de datos para la tabla `usuarios`
            --
            
            INSERT INTO `usuarios` (`id`, `username`, `password`) VALUES
            (3, "webadmin", "$2y$10$Jt/hYuwyshglWwa1fC4Bm..IH99/aSrFY.juEBKRAIRaZPyxrGO66"),
            (4, "pedro", "$2y$10$3ABJQBoQSZs7w//p2j60aeNQcAh9IJW4oCnVMnl4Ttmu3EWFTmGem");';
            $conn->exec($sql);

            $sql = '--
            -- Índices para tablas volcadas
            --
            
            --
            -- Indices de la tabla `directores`
            --
            ALTER TABLE `directores`
              ADD PRIMARY KEY (`id`);
            
            --
            -- Indices de la tabla `peliculas`
            --
            ALTER TABLE `peliculas`
              ADD PRIMARY KEY (`id`),
              ADD KEY `fk_peliculas_directores` (`director`);
            
            --
            -- Indices de la tabla `usuarios`
            --
            ALTER TABLE `usuarios`
              ADD PRIMARY KEY (`id`);
            
            --
            -- AUTO_INCREMENT de las tablas volcadas
            --
            
            --
            -- AUTO_INCREMENT de la tabla `directores`
            --
            ALTER TABLE `directores`
              MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
            
            --
            -- AUTO_INCREMENT de la tabla `peliculas`
            --
            ALTER TABLE `peliculas`
              MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;
            
            --
            -- AUTO_INCREMENT de la tabla `usuarios`
            --
            ALTER TABLE `usuarios`
              MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
            
            --
            -- Restricciones para tablas volcadas
            --
            
            --
            -- Filtros para la tabla `peliculas`
            --
            ALTER TABLE `peliculas`
              ADD CONSTRAINT `fk_peliculas_directores` FOREIGN KEY (`director`) REFERENCES `directores` (`id`);
            COMMIT;';
            $conn->exec($sql);

            return $conn;
        }
    }
    public function getByID($id)
    {
        $query = $this->db->prepare("SELECT * from $this->table WHERE id = ?");
        $query->execute([$id]);
        return $query->fetch(PDO::FETCH_OBJ);
    }

    public function getAll()
    {
        $query = $this->db->prepare("SELECT * from $this->table");
        $query->execute();
        return $query->fetchAll(PDO::FETCH_OBJ);
    }

    public function deleteByID($id)
    {
        $query = $this->db->prepare("DELETE from $this->table WHERE id = ?");
        return $query->execute([$id]);
    }
}
