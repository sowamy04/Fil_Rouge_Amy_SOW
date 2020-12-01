<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\AdminRepository;
use ApiPlatform\Core\Annotation\ApiResource;

/**
 * @ORM\Entity(repositoryClass=AdminRepository::class)
 * @ApiResource(
 *  attributes={
 *      "input_formats"={"json"={"application/ld+json", "application/json"}},
 *      "output_formats"={"json"={"application/ld+json", "application/json"}},
 *      "deserialize"=false,
 *        "swagger_context"={
 *           "consumes"={
 *              "multipart/form-data",
 *             },
 *             "parameters"={
 *                "in"="formData",
 *                "name"="file",
 *                "type"="file",
 *                "description"="The file to upload",
 *              },
*           },
 *     },
 *  collectionOperations={
 *      "add_admin":{
 *          "method" : "POST",
 *          "path":"admin/users",
 *          "normalization_context"={"groups":"user:read"},
 *          "access_control"="(is_granted('ROLE_ADMIN'))",
 *          "access_control_message"="Vous n'avez pas access à cette Ressource",
 *          "route_name" = "add_admin",
 *      },  
 *  },
 *  itemOperations={
 *      "modifier_admin":{
 *          "method" : "PUT",
 *          "path":"admin/users/{id}",
 *          "normalization_context"={"groups":"user:read"},
 *          "access_control"="(is_granted('ROLE_ADMIN'))",
 *          "access_control_message"="Vous n'avez pas access à cette Ressource",
 *          "route_name" = "update_admin"
 *      },
 *  }
 * )
 */
class Admin extends User
{
    
}
