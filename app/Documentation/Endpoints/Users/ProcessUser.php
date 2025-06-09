<?php

namespace App\Documentation\Endpoints\Users;

/**
 * @OA\Post(
 *     path="/api/v1/users/process",
 *     summary="Processa um novo usuário",
 *     description="Cria um novo usuário com os dados fornecidos e inicia a análise de risco",
 *     operationId="processUser",
 *     tags={"Users"},
 *     security={{"bearerAuth": {}}},
 *     @OA\RequestBody(
 *         required=true,
 *         description="Dados do usuário para processamento",
 *         @OA\JsonContent(ref="#/components/schemas/ProcessUserRequest")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Usuário processado com sucesso",
 *         @OA\JsonContent(
 *             @OA\Property(
 *                 property="data",
 *                 ref="#/components/schemas/UserResource"
 *             ),
 *         )
 *     ),
 *     @OA\Response(
 *         response=422,
 *         description="Validação falhou",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="O CPF fornecido é inválido")
 *         )
 *     ),
 *     @OA\Response(
 *         response=500,
 *         description="Erro interno no processamento",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="Falha ao processar usuário, tente novamente mais tarde.")
 *         )
 *     )
 * )
 */
class ProcessUser {}
