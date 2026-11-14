<?php
class ZoneRepository
{
    private string $table = 'zones';

    public function create(Zone $Zone): string
    {
        $pg_PDO = PDOConnection::pg_get();
        
        $query = "INSERT INTO " . $this->table . " 
                    (name, classification, description,
                    geom, 
                    aire)
                VALUES 
                    (:name, :classification, :description, 
                    ST_SetSRID(ST_GeomFromGeoJSON(:geom), 4326),
                    ST_Area(ST_GeomFromGeoJSON(:geom)::geography)
                    )";

        $statement = $pg_PDO->prepare($query);
        $statement->bindValue(':name', $Zone->getName(), PDO::PARAM_STR);
        $statement->bindValue(':classification', $Zone->getClassification(), PDO::PARAM_INT);
        $statement->bindValue(':description', $Zone->getDescription(), PDO::PARAM_STR);
        
        // Correction 2 : Construction d'un objet GeoJSON valide
        // PostGIS a besoin du "type" et de la structure complète
        $geoJSON = json_encode([
                'type' => 'Polygon',
                'coordinates' => [$Zone->getCoordinates() ]
            ]); // besoin d'un tableau de 3 dimensions pour un POLYGON

        $statement->bindValue(':geom', $geoJSON, PDO::PARAM_STR);
        $statement->bindValue(':geom', $geoJSON, PDO::PARAM_STR);
        $statement->execute();


        return $pg_PDO->lastInsertId();
    }

    public function getOne(int $id): array
    {
        $pg_PDO = PDOConnection::pg_get();
        // On spécifie les colonnes et on transforme la géométrie comme dans le getAll
        $query = "SELECT id, 
                        name, 
                        classification, 
                        description, 
                        aire, 
                        ST_AsGeoJSON(geom)::json->'coordinates' AS coordinates 
                FROM " . $this->table . " 
                WHERE id = :id";

        $statement = $pg_PDO->prepare($query);
        $statement->bindValue(':id', $id, PDO::PARAM_INT);
        $statement->execute();
        
        $row = $statement->fetch(PDO::FETCH_ASSOC);
        // Sécurité : si l'ID n'existe pas, fetch renvoie false
        if (!$row) {
            return [];
        }
        // Décodage des coordonnées (car PostgreSQL renvoie une chaîne JSON)
        $row['coordinates'] = json_decode($row['coordinates'], true);

        $zoneGetDTO = new ZoneGetDTO();
        $zoneGetDTO->hydrate($row);

        return $zoneGetDTO->toArray();
    }

    public function getAll(): array
    {
        $pg_PDO = PDOConnection::pg_get();
        // ST_AsGeoJSON(geom)::json->'coordinates' AS coordinates récupère les coordonnées en TABLEAU
        $query = "SELECT id, 
                        name, 
                        classification,
                        description,
                        aire, 
                        ST_AsGeoJSON(geom)::json->'coordinates' AS coordinates
                FROM " . $this->table . 
                " WHERE geom IS NOT NULL AND NOT ST_IsEmpty(geom)" ;
        $statement = $pg_PDO->query($query);
        $data = [];
        while($row = $statement->fetch(PDO::FETCH_ASSOC)){
            
            $row['coordinates'] = json_decode($row['coordinates'], true);
            $zoneGetDTO = new ZoneGetDTO();
            $zoneGetDTO->hydrate($row);
            
            $data[] = $zoneGetDTO->toArray();
        }
        return $data;
    }

    public function update(ZoneGetDTO $zonePatch): int
    {
        $pg_PDO = PDOConnection::pg_get();

        $query = "UPDATE " . $this->table . " 
                    SET 
                        name = :name,
                        classification = :classification,
                        description = :description
                    WHERE id = :id";
        $statement = $pg_PDO->prepare($query);
        $statement->bindValue(':name', $zonePatch->getName(), PDO::PARAM_STR);
        $statement->bindValue(':classification', $zonePatch->getClassification(), PDO::PARAM_INT);
        $statement->bindValue(':description', $zonePatch->getDescription(), PDO::PARAM_STR);
        $statement->bindValue(':id', $zonePatch->getId(), PDO::PARAM_INT);
        $statement->execute();
        return $statement->rowCount();
    }

        public function delete(int $id): void
        {
            $pg_PDO = PDOConnection::pg_get();
            $query = "DELETE FROM " . $this->table . " WHERE id = :id";
            $statement = $pg_PDO->prepare($query);
            $statement->bindValue(':id', $id, PDO::PARAM_INT);
            $statement->execute();
        }



}
