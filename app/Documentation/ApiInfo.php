<?php

namespace App\Documentation;

/**
 * @OA\Info(
 *     title="Paynet API",
 *     version="1.0.0",
 *     description="API para integração com o sistema Paynet",
 *     termsOfService="https://paynet.com/terms",
 *     @OA\Contact(
 *         email="api@paynet.com"
 *     ),
 *     @OA\License(
 *         name="Proprietary",
 *         url="https://paynet.com/license"
 *     )
 * )
 * 
 * @OA\Server(
 *     url=L5_SWAGGER_CONST_HOST,
 *     description="API Server"
 * )
 * 
 * @OA\Tag(
 *     name="Users",
 *     description="Operações relacionadas a usuários"
 * )
 * 
 * @OA\Tag(name="Users", description="Operações de usuário")
 * @OA\Tag(name="Requests/Users", description="Modelos de requisição de usuário")
 * @OA\Tag(name="Users/Responses", description="Modelos de resposta de usuário")
 * @OA\Tag(name="Shared/Errors", description="Modelos de erro compartilhados")
 */
class ApiInfo {}
