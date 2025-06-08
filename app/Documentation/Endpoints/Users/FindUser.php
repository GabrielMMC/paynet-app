<?php

namespace App\Documentation\Endpoints\Users;

/**
 * @OA\Get(
 *     path="/api/v1/users/{cpf}",
 *     summary="Busca um usuário pelo CPF",
 *     description="Retorna os dados completos de um usuário, incluindo endereço e perfil financeiro",
 *     operationId="findUserByCpf",
 *     tags={"Users"},
 *     security={{"bearerAuth": {}}},
 *     @OA\Parameter(
 *         name="cpf",
 *         in="path",
 *         required=true,
 *         description="CPF do usuário (com ou sem formatação)",
 *         @OA\Schema(
 *             type="string",
 *             pattern="^\d{3}\.?\d{3}\.?\d{3}-?\d{2}$",
 *             example="123.456.789-09"
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Dados do usuário encontrado",
 *         @OA\JsonContent(ref="#/components/schemas/UserDataResource")
 *     ),
 *     @OA\Response(
 *         response=422,
 *         description="CPF inválido",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="O CPF fornecido é inválido")
 *         )
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Usuário não encontrado",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="Usuário de CPF 123.456.789-09 não encontrado")
 *         )
 *     ),
 *     @OA\Response(
 *         response=500,
 *         description="Erro interno",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="Falha ao buscar por usuário, tente novamente mais tarde")
 *         )
 *     )
 * )
 */
class FindUser {}
